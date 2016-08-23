<?php
namespace Capturada\Model;

use Common\Exception\ArquivoNaoInseridoException;

use Capturada\Entity\UsuarioCapturada;

use Capturada\Entity\Capturada;

use Extended\Model\CapturaModel;

use User\Auth\CapturaAuth;

class CapturadaModel extends CapturaModel{
    
  
    /**
     * Método que verifica se a sessão foi morta pelo usuário
     * @param Capturada $capturada objeto em que verifica-se se existe o usuário no mesmo
     * ou se a sessão foi morta.
     * @throws \Common\Exception\SessaoMortaException
     */
    public function verificaSessao()
    {
    	if(!CapturaAuth::getInstance()->getUser()){
    		throw new \Common\Exception\SessaoMortaException();
    	}
    }
    
    /**
     * Método que verifica se existe o arquivo enviado, caso não exista
     * é lançada uma nova exception.
     * @throws \Common\Exception\SemArquivoException
     */
    public function verificaArquivo($file)
    {
    	if($file == null || !array_key_exists('tmp_name', $file) ){
    		throw new \Common\Exception\SemArquivoException();
    	}
    }
    
    /**
     * Cria um objeto do tipo capturada para o caso de multiplas capturadas
     * @param array $file objeto do tipo $_FILE
     * @param parameters $post objeto do tipo $request->getPost()
     */
    public function createCapturaObject($file, $post)
    {
        $obj = Capturada::getInstance()->setNome($post->nome)->setDescricao($post->descricao)->setTemp($file['tmp_name'])->setAprovada('false')->setUser(CapturaAuth::getInstance()->getUser()->id_usuario);
        $obj->setHash($obj->getHash());
        return $obj;
    }
    
    /**
     * Método que verifica se está com a extensão válida para o site.
     * @param Array $arquivo Objeto do tipo $_FILE
     * @throws \Common\Exception\ExtensaoArquivoCapturadaInvalidoException Exception
     * caso a extensão do arquivo seja diferente da requisitada no site.
     */
    public function verificaExtensao($arquivo)
    {
    	if($arquivo && !preg_match("/^image\/(jpeg|jpg)$/", $arquivo['type'])){
    		throw new \Common\Exception\ExtensaoArquivoCapturadaInvalidoException();
    	}
    }
    
    /**
     * Método que verifica se existe o arquivo na pasta e se existe
     * no banco de dados.
     * @param Capturada $capturada Objeto com os dados da imagem capturada.
     * @throws \Common\Exception\CapturadaExistenteException exception se caso existir a foto
     * no banco de dados.
     * @throws \Common\Exception\ArquivoCapturadaExistenteException exception se caso existir
     * o arquivo na pasta do usuário.
     */
    public function existeCapturada(Capturada $capturada)
    {
    	$msg = null;
    	if($this->getTable('Capturada', 'Capturada')->getCapturadaPorHash($capturada)->count()){
    		throw new \Common\Exception\CapturadaExistenteException();
    	}
    }
    
    /**
     * Método que insere uma foto capturada.
     * @param Capturada $capturada Objeto do tipo Capturada.
     */
    public function salvar(&$capturada)
    {
    	$capturada->id_capturada = $this->getTable('capturada')->save($capturada);
    	$usuarioCapturada = new UsuarioCapturada();
    	$usuarioCapturada->id_capturada = $capturada->id_capturada;
    	$usuarioCapturada->id_usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
    	$usuarioCapturada->id_capturada_anterior = $this->getTable('usuarioCapturada')->getUltimaCapturada(CapturaAuth::getInstance()->getUser()->id_usuario);
    	$this->getTable('UsuarioCapturada','capturada')->insert($usuarioCapturada->toArray());
    }
    
    /**
     * Método que cria uma pasta publica
     * @param String $path Local até a pasta em questão
     */
    public function createPublicFolder($path)
    {
    	if(!is_dir($path)){
    		mkdir($path);
    		chmod($path,0777);
    		return true;
    	}
    	return false;
    }
    
    /**
     * Método que faz a cópia da imagem capturada para a pasta raiz do usuário.
     * @param Capturada $capturada objeto do tipo capturada.
     * @return boolean
     */
    public function copiarCapturada(Capturada $capturada)
    {
    	// verificando se a pasta raiz existe, caso não exista deve ser criada a pasta
    	$criado = $this->createPublicFolder($capturada->getPastaUsuario());

    	// copiando o arquivo
    	move_uploaded_file($capturada->getTemp(), $capturada->getPath());
    
    	// Permissão para a nova capturada
    	chmod($capturada->getPath(),0777);
    
    	if(!file_exists($capturada->getPath())){
    		throw new ArquivoNaoInseridoException();
    	}
    
    	return true;
    }
    
    /**
     * Método que cria miniatura e arquivos medianos da imagem original.
     * @param Capturada $capturada objeto do tipo capturada.
     */
    public function gerarImagensExtras(Capturada $capturada)
    {
        
        // criando a pasta thumb se for necessário
        $this->createPublicFolder("{$capturada->getPastaUsuario()}/thumb");
        
        // criando a thumb
        $capturada->getThumb(60,60);
        
        // criando a pasta view se for necessário
        $this->createPublicFolder("{$capturada->getPastaUsuario()}/view");
        
        // criando mediana para larguras abaixo de 768
        $capturada->getImagem(820, 546);
        
        // criando mediana para larguras abaixo de 960
        $capturada->getImagem(1000,666);
        
        // criando mediana para larguras abaixo de 1050
        $capturada->getImagem(1178,784);
    }
    
}
