<?php

namespace Capturada\Entity;

use User\Entity\Window;

use Extended\Table\CapturaObject;

class Capturada extends CapturaObject
{

    /**
     *
     * @name ='id_capturada'
     *       @type='integer'
     */
    protected $id_capturada;

    /**
     *
     * @name ='aprovada'
     *       @type='boolean'
     */
    protected $aprovada;

    /**
     *
     * @name ='hash'
     *       @type='string'
     */
    protected $hash;

    /**
     *
     * @name ='caminho'
     *       @type='string'
     */
    protected $caminho;

    /**
     *
     * @name ='nome'
     *       @type='string'
     */
    protected $nome;

    /**
     *
     * @name ='descricao'
     *       @type='string'
     */
    protected $descricao;

    /**
     *
     * @name ='dtImagem'
     *       @type='string'
     */
    protected $dtImagem;
    
    /**
     *
     * @name ='pegou'
     *       @type='Integer'
     */
    protected $pegou;
    
    /**
     *
     * @name ='naoPegou'
     *       @type='Integer'
     */
    protected $naoPegou;

    /**
     * @exclude
     */
    public $exif;
    
    /**
     * @exclude
     */
    public $arquivo;
    
    /**
     * @exclude
     */
    public $temp;

    /**
     * @exclude
     */
    public $type = 'jpg';
    
    /**
     * @exclude
     */
    public $user = null;
    
    /**
     * @exclude
     */
    public $height = null;
    
    /**
     * @exclude
     */
    public $width = null;


    
    /**
     * Método que retorna qual é o caminho da pasta med
     */
    public function getCaminhoMed ()
    {
    	return constant('PUBLIC') . "capturadas/{$this->user}/med/";
    }
    
    /**
     * Método que retorna qual é o caminho da pasta thumb
     */
    public function getCaminhoThumb ()
    {
    	return constant('PUBLIC') . "capturadas/{$this->user}/thumb/";
    }
    
    /**
     * Método que retorna qual é o lado maior, altura ou largura.
     */
    public function getMaior()
    {
        $size = getimagesize($this->getPath());
        $this->width = $size[0];
        $this->height = $size[1];
        return $this->width > $this->height ? "Largura" : "Altura";    
    }

    /**
     * Regra de como gerar o hashcode de cada foto.
     * 
     * @return string Hash da foto capturada.
     */
    public function getHash ()
    {
        return md5($this->getBin());
    }

    /**
     * Método que retorna o binário da foto capturada.
     * 
     * @throws \Exception caso não for setada a variável $temp
     *         lança exception requisitando que a mesma seja setada.
     */
    public function getBin ()
    {
        if ($this->temp == null)
        {
            throw new \Exception('Necessita setar a variável temporaria');
        }
        
        $fd = fopen($this->temp, 'rb');
        $contents = fread($fd, filesize($this->temp));
        fclose($fd);
        return base64_encode($contents);
    }


    /**
     * Método que retorna o nome da imagem
     * 
     * @return string
     */
    public function getNome ()
    {
        if ($this->nome)
        {
            $nome = strtolower(str_replace(' ', '_', $this->nome));
            return $nome;
        }
    }
       
    /**
     * Método que retorna o nome do arquivo.
     * @return string
     */
    public function getArquivo ()
    {
        if(!$this->arquivo){
            $this->arquivo = md5($this->getId());
        }
        return $this->arquivo;
    }


    /**
     * Método que mostra o caminho temporário do arquivo no servidor.
     */
    public function getTemp ()
    {
        return $this->temp;
    }

    /**
     *
     * @return the $id_capturada
     */
    public function getId ()
    {
        return $this->id_capturada;
    }
    
    /**
     * Método que retorna qual é a pasta do usuário da capturada em questão.
     */
    private function getPastaPublicaUsuario()
    {
    	return "/capturadas/{$this->user}";
    }
    	
    /**
     * Método que retorna o caminho até a pasta do usuário
     */
    public function getPastaUsuario () {
    	return constant('PUBLIC') . "capturadas/{$this->user}";
    }
    
    /**
     * Método que retorna o caminho até o arquivo original.
     */
    public function getPath($pasta = '', $resolucao = '')
    {
        return "{$this->getPastaUsuario()}/{$pasta}{$resolucao}{$this->getArquivo()}.{$this->type}";
    }

    /**
     * Método que retorna o view path de uma imagem
     * @param String $pasta pasta onde está a imagem.
     * @param Integer $largura largura da imagem.
     * @param Integer $altura altura da imagem.
     * @param Boolean $fill se vai preencher ou não.
     * @return string
     */
    public function getViewPath($pasta = '', $largura = null, $altura = null, $fill = false)
    {
        $resolucao =  $largura ? "{$largura}x{$altura}" : '';
        $imagem = $this->getPath($pasta, $resolucao);
        $this->exif = Exif::factory($this->getPath());
        
        // verifica se o arquivo existe
        if(!file_exists($imagem))
        {
            // caso ele não exista tente criar a partir da origem
            $fill ?  \Library\Wideimage\WideImage::load($this->getPath())->resize($largura, $altura, 'fill')->saveToFile($imagem,70)
                  : \Library\Wideimage\WideImage::load($this->getPath())->resize($largura, $altura, 'inside', 'down')->saveToFile($imagem,70);
            
            // adicionando permissão para a imagem
            chmod($imagem,0777);
        }
        return "{$this->getPastaPublicaUsuario()}/{$pasta}{$resolucao}{$this->getArquivo()}.{$this->type}";
    }
    
    /**
     * Método que retorna a imagem a ser exibida ao usuário.
     * @param Integer $largura largura da imagem.
     * @param Integer $altura altura da imagem.
     * @return string
     */
    public function getImagem($largura = 1200, $altura = 766)
    {
        if($largura == 1200 && $altura == 766)
        {
            $window = Window::getInstance()->getWindow();
            if($window){
                if($window->height <= 768){
                    $largura = 820;
                    $altura = 546;
                } elseif($window->height <= 960){
                    $largura = 1000;
                    $altura = 666;
                } elseif($window->height <= 1050){
                    $largura = 1178;
                    $altura = 784;
                }
            } else {
                $largura = 820;
                $altura = 546;
            }
        }
        return $this->getViewPath('view/', $largura, $altura, false);
    }
    
    /**
     * Método que retorna a imagem thumb da original
     * @param Integer $largura largura da imagem.
     * @param Integer $altura altura da imagem.
     */
    public function getThumb($largura = 60, $altura = 60)
    {
        return $this->getViewPath('thumb/', $largura, $altura, true);
    }
}