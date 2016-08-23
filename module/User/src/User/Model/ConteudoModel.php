<?php
namespace User\Model;
use User\Entity\Revista;

use Common\Exception\ConteudoInexistenteException;

use Common\Exception\UsuarioDiferenteException;

use User\Auth\CapturaAuth;

use User\Entity\TipoConteudo;

use User\Entity\Conteudo;

use Common\Exception\CpfExistenteException;

use Common\Exception\LoginExistenteException;

use Common\Exception\UsuarioInvalidoException;

use User\Entity\User;
use Zend\Form\Form;
use Zend\Mail;
use Extended\Model\CapturaModel;
use Common\Exception\UsuarioInativoException;

class ConteudoModel extends CapturaModel{
    
    /**
     * Método que insere um conteúdo
     * @param Form $form formulário a ser cadastrado
     */
    public function salvarConteudo(Form $form, $tipoConteudo, $idUsuario)
    {
        $conteudo = new Conteudo();
        $conteudo->exchangeArray(array_merge($form->getData(),array(
            'id_tipo' => $tipoConteudo, 'id_usuario' => $idUsuario
        )));
        return $this->getTable('conteudo')->save($conteudo);
    }
    
    /**
     * Método que insere um conteúdo
     * @param Form $form formulário a ser cadastrado
     */
    public function salvar(Conteudo $conteudo)
    {
        return $this->getTable('conteudo')->save($conteudo);
    }
    
    /**
     * Método que insere um conteúdo
     * @param Integer $idUsuario identificador do usuário
     * @param Integer $idConteudo identificador do conteudo
     */
    public function deletarConteudo($idUsuario, $idConteudo)
    {
        $objConteudo = Conteudo::getNewInstance()->fillinArray(array('id_usuario'=> $idUsuario, 'id_conteudo'=> $idConteudo));
        return $this->getTable('conteudo')->deleteConteudo($objConteudo);
    }
    
    /**
     * Método que verifica o usuário informado é o mesmo
     * da sessão.
     * @param Integer $idUsuario identificador do usuário
     */
    public function verificarUsuario($idUsuario)
    {
        if(CapturaAuth::getInstance()->getUser()->id_usuario != $idUsuario){
            throw new UsuarioDiferenteException();
        } 
    }

    /**
     * Método que verifica se existe o conteudo
     * @param Integer $idUsuario identificador do usuário
     */
    public function verificarConteudoExistente($idConteudo)
    {
        $resultado = $this->getTable('conteudo')->findAll(array('id_conteudo' => $idConteudo));
        if($resultado->count() == 0){
            throw new ConteudoInexistenteException();
        } 
    }
    
    /**
     * Método que retona um tipo de conteudo
     * @param String $nomeConteudo nome do tipo do conteudo 
     * requisitado
     */
    public function getTipoConteudo($nomeConteudo)
    {
        $tipoConteudo = TipoConteudo::getInstance()->fillInArray(array('tipo' => $nomeConteudo));
        $tipoConteudo->exchangeArray($this->getTable('tipoConteudo')->getTipoConteudo($tipoConteudo)->current());
        return $tipoConteudo;
    }
    
    /**
     * Método que monta um conteudo do tipo Revista
     */
    public function getConteudoRevista($form, $tipo)
    {
        $revista = Revista::getNewInstance()->fillInArray($form);
        $revista->updateByArray(array(
            'id_usuario' =>  CapturaAuth::getInstance()->getUser()->id_usuario,
            'id_tipo' => $tipo->id_tipo_conteudo,
            'conteudo' => $form['descricao'],
            'link' => $revista->getCaminhoArquivoPublico(),

        ));
        
        return $revista;
    }
    
    /**
     * Metodo quer cria uma pasta se ela não existir
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
     * Método que copia uma revista
     */
    public function copiarRevista(Revista $revista)
    {
        
    	// verificando se a pasta raiz existe, caso não exista deve ser criada a pasta
    	$criado = $this->createPublicFolder($revista->getCaminhoRevista());
    
    	// salvando o arquivo
    	move_uploaded_file($revista->file['tmp_name'], $revista->getCaminhoArquivo());
    	 
    	// Permissão para a nova capturada
    	chmod($revista->getCaminhoRevista(),0777);
    	chmod($revista->getCaminhoArquivo(),0777);
    	return true;
    }
    
    
    /**
     * Método que retorna conteudos de um determinado tipo;
     * @param TipoConteudo $tipoCapturada tipo do conteudo em questão;
     */
    public function getConteudos($tipoConteudo, $pagina=1, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $conteudos =  $this->getTable('conteudo')->getConteudos($tipoConteudo, $limit, $offset);
        $retorno = array();
        foreach($conteudos as $conteudo){
            $retorno[] = Conteudo::getNewInstance()->fillInArray($conteudo);
        }
        return $retorno;
    }
    
    /**
     * Método que retorna a timeline
     */
    public function getTimeLine($pagina=1, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $conteudos =  $this->getTable('conteudo')->getTimeline($limit, $offset);
        $retorno = array();
        foreach($conteudos as $conteudo){
            $retorno[] = Conteudo::getNewInstance()->fillInArray($conteudo);
        }
        return $retorno;
    }
    
    /**
     * Método que retorna o ultimo conteúdo
     * @param TipoConteudo $tipoCapturada tipo do conteudo em questão;
     */
    public function getUltimoConteudo(TipoConteudo $tipoConteudo)
    {
        $ultimo =  $this->getTable('Conteudo', 'User')->getUltimoConteudo($tipoConteudo);
        return $ultimo->count() ?  Conteudo::getNewInstance()->fillInArray($ultimo->current()) : null;
    }
}