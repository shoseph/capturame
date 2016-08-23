<?php 
ini_set("memory_limit","512M");
set_time_limit(99999999999);
require_once 'Wideimage/WideImage.php';
// include_once('terminal.php');

chdir(dirname(__DIR__));

class MoveFiles
{
    public $localAcao;
    public $usuario;
    
    public function __construct($usuario = null)
    {
        if($usuario){
            $this->usuario = $usuario;
            $this->localAcao = getcwd() . '/public/capturadas/' . $this->usuario;
        } else {
            $this->localAcao = getcwd() . '/public/capturadas';
        }
    }
    
    /**
     * Método que remove todos os itens dentro da pasta antes de 
     * remover a pasta em questão.
     * @param String $path pasta para ser removida com todos os
     * arquivos e pastas dentro da mesma.
     */
    function removeDir($path) {
    
    	// Normalise $path.
    	$path = rtrim($path, '/') . '/';
    
    	// Remove all child files and directories.
    	$items = glob($path . '*');
    
    	foreach($items as $item) {
    		is_dir($item) ? removeDir($item) : unlink($item);
    	}
    
    	// Remove directory.
    	if (is_dir($path)) rmdir($path);
    }
    
    /**
     * Método que informa quais são as pastas que devem ser deletadas.
     * @param RecursiveIterator $ri iterador da pasta atual
     */
    public function deletarPastas($pasta)
    {
        $pastaMed = "{$pasta}/med";
        $pastaThumb = "{$pasta}/thumb";
        $pastaView = "{$pasta}/view";
        
        if(is_dir($pastaMed)){
            $this->removeDir($pastaMed);
        }
        if(is_dir($pastaThumb)){
            $this->removeDir($pastaThumb);
        }
        if(is_dir($pastaView)){
            $this->removeDir($pastaView);
        }
        
    }

    /**
     * Método que cria a imagem
     * @param String $origem onde a imagem original está.
     * @param String $pastaDestino caminho até a pasta onde vai ficar a nova imagem.
     * @param Integer $largura Largura da imagem.
     * @param Integer $altura Altura da imagem.
     * @param String $imagem Nome da imagem
     * @param Boolean $fill Se vai ou não fazer o fill.
     */
    public function criarImagem($origem, $pastaDestino, $largura, $altura, $imagem,  $fill)
    {
        // caso o diretório não exista deve criar o diretório
        if(!is_dir($pastaDestino)){
        	mkdir($pastaDestino);
        	chmod($pastaDestino,0777);
        }
        
        $destino = "{$pastaDestino}/{$largura}x{$altura}{$imagem}";

        // criando a imagem
        $fill ? WideImage::load($origem)->resize($largura, $altura, 'fill')->saveToFile($destino,70)
              : WideImage::load($origem)->resize($largura, $altura, 'inside', 'down')->saveToFile($destino,70);
        
        // dando permissão de leitura a imagem
        chmod($destino,0777);
    }
    
    /**
     * Método que conta a quantidade de pastas em um diretorio
     * @param unknown_type $ri
     */
    public function countFolders($ri)
    {
        $folders = 0;
        foreach($ri as $folder)
        {
            if($ri->getBasename() == '.' || $ri->getBasename() == '..'){
            	continue;
            }
            
            if($folder->isDir()){
                $folders++;
            }    
        }
        return $folders;
    }
    
    /**
     * Método que conta a quantidade de arquivos em um diretorio
     * @param unknown_type $ri
     */
    public function countFiles($ri)
    {
        $files = 0;
        foreach($ri as $file)
        {
            if($ri->getBasename() == '.' || $ri->getBasename() == '..'){
            	continue;
            }
            
            if($file->isFile()){
                $files++;
            }    
        }
        return $files;
    }
    
    /**
     * Método que executa a regra de criação de imagens extras.
     * @param RecursiveIterator $ri Iterator com a pasta onde deve iniciar o processo
     * de criaação e remoção de arquivos.
     */
    public function criarImagensExtras($pasta)
    {
        $pastaMed = $pasta . '/med';
        $pastaThumb = $pasta. '/thumb';
        $pastaView = $pasta . '/view';

        $arquivosNaPasta = new RecursiveDirectoryIterator($pasta);
        $tamanho = $this->countFiles($arquivosNaPasta);
        $percent = 0;
        $indice = 0;
        foreach($arquivosNaPasta as $arquivo)
        {
            
            if($arquivo->getBasename() == '.' || $arquivo->getBasename() == '..' || $arquivo->getBasename() == 'thumb'
                    || $arquivo->getBasename() == 'view' || $arquivo->getBasename() == 'view' ){
            	continue;
            }

            // criando a thumb
            $this->criarImagem($arquivo->getPathname(), $pastaThumb, 60, 60, $arquivo->getFilename(), true);
            
            // criando mediana para larguras abaixo de 768
            $this->criarImagem($arquivo->getPathname(), $pastaView, 820, 546, $arquivo->getFilename(), false);
            
            // criando mediana para larguras abaixo de 960
            $this->criarImagem($arquivo->getPathname(), $pastaView, 1000, 666, $arquivo->getFilename(), false);
            
            // criando mediana para larguras abaixo de 1050
            $this->criarImagem($arquivo->getPathname(), $pastaView, 1178, 784, $arquivo->getFilename(), false);
            
            $percent = $percent >= 100 ? 100 : (int) (( (++$indice)  * 100) / $tamanho);
            if($indice %5 ==0) {
            	print(' '. $percent . '% ');
            }
            
        }
            
    }
    
    /**
     * Método que executa a ação de rodar o programa
     * @return boolean
     */
    public function run()
    {
        $raiz  = new RecursiveDirectoryIterator($this->localAcao);
        if($this->usuario){
            $this->deletarPastas($this->localAcao);
            print("Usuario[{$this->usuario}]: ");
            $this->criarImagensExtras($this->localAcao);
            print(" 100% \n");
            return true;
        }
        $tamanho = $this->countFolders($raiz);
        
        // caso for a raiz das pastas, deve fazer em todas as pastas
        foreach($raiz as $pastaUsuario){
            
            if($pastaUsuario->getBasename() == '.' || $pastaUsuario->getBasename() == '..'
                    || $pastaUsuario->getBasename() == 'thumb' || $pastaUsuario->getBasename() == 'view'
                    || $pastaUsuario->getBasename() == 'med'){
                continue;
            }
            print("Falta(m) [{$tamanho}] usuário(s) \n");
            $this->usuario = $pastaUsuario->getBasename();
            $localAcao = "{$this->localAcao}/{$this->usuario}";
            $this->deletarPastas($localAcao);
            print("Usuario[{$this->usuario}]: ");
            $this->criarImagensExtras($localAcao);
            print(" 100% \n");
            $tamanho--;
            
        }
    }
}

$obj = array_key_exists(1,$argv) ? new MoveFiles($argv[1]) : new MoveFiles();
$obj->run();
