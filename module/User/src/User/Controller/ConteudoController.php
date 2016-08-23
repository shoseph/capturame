<?php
namespace User\Controller;

use Extended\Object\FacebookNoticias;

use Common\Exception\SessaoMortaException;

use Common\Exception\CpfExistenteException;

use Common\Exception\LoginExistenteException;

use Common\Exception\UsuarioInvalidoException;
use Common\Exception\UsuarioInativoException;
use User\Auth\CapturaAuth;

use Extended\Controller\CapturaController;

use User\Entity\User;

use User\Form\RegistrarFormFilter;

use Zend\Form\Form;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use User\Service\User as UserService;
use User\Options\UserControllerOptionsInterface;


// retirar depois

use \Zend\Permissions\Acl\Acl;
class ConteudoController extends CapturaController
{
    /**
     * Action que lista os conteudos
     */    
    public function indexAction()
    {
        $retorno = array();
        $retorno['dicas'] = $this->getModel()->getConteudos($this->getModel()->getTipoConteudo('dica'));
        $retorno['novidades'] = $this->getModel()->getConteudos($this->getModel()->getTipoConteudo('novidade'));
        $retorno['artigos'] = $this->getModel()->getConteudos($this->getModel()->getTipoConteudo('artigo'));
        return new ViewModel($retorno);
    }
    
    
    public function dicasAction()
    {
        $retorno['dicas'] = $this->getModel()->getConteudos($this->getModel()->getTipoConteudo('dica'));
        return new ViewModel($retorno);
    }
    
    public function revistasAction()
    {
        $retorno['revistas'] = $this->getModel()->getConteudos($this->getModel()->getTipoConteudo('revista'));
        return new ViewModel($retorno);
    }
    
    public function adicionarRevistaAction()
    {
        $retorno = array();
        try{
        	$this->getModel()->verificaSessao();
        	$form    = $this->getForm('adicionarRevista');
        	$request = $this->getRequest();
        	
        	if($request->isPost())
        	{    
        	    $file = $this->getFiles('arquivo');
        	    $form->setData($request->getPost()->toArray());
        	    
        		if ($form->isValid()) {
        		    
        	        // tipo do conteudo revista
        			$tipoConteudo = $this->getModel()->getTipoConteudo('revista');
        			
        	        // cria objeto conteudo do tipo revista
        			$revista = $this->getModel()->getConteudoRevista($form->getData(), $tipoConteudo);
        			$revista->file = $file;
        			
        			// copia o arquivo para a pasta de revistas
        			$this->getModel()->copiarRevista($revista);
        			
        			// insere no banco
        			$this->getModel()->salvar($revista);
        			
        			// informa no facebook
        			FacebookNoticias::factory()->sendConteudo($tipoConteudo->tipo, $revista->titulo, strip_tags($revista->conteudo));
        			
        			// mensagem de sucesso
        		    $this->flashMessenger()->addMessage($this->getMsg('Revista adicionada com sucesso!'));
        		    return $this->redirect()->toRoute('home');
        		    
        		} 
        	}

        } catch (SessaoMortaException  $e){
        	return $this->json($e->getMessage(), false);
        }
        $retorno['form'] = $form;
        return new ViewModel($retorno);
    }
    
    /**
     * Action que adiciona uma novidade
     */    
    public function adicionarNovidadeAction()
    {
        $retorno = array();
   	    try{
   	        $this->getModel()->verificaSessao();
            $form    = $this->getForm('adicionarNovidade');
            $request = $this->getRequest();
            if($request->isPost())
            {
            	$form->setData($request->getPost()->toArray());
            	if ($form->isValid()) {
            	    
                	    $tipoConteudo = $this->getModel()->getTipoConteudo('novidade');
                	    $this->getModel()->verificaSessao();
                	    $usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
                	    $data = $form->getData();
                	    FacebookNoticias::factory()->sendConteudo($tipoConteudo->tipo, $data['titulo'], strip_tags($data['conteudo']));
                	    $this->getModel()->salvarConteudo($form, $tipoConteudo->id_tipo_conteudo, $usuario);
               			$this->flashMessenger()->addMessage($this->getMsg('Novidade adicionada com sucesso!'));
                        return $this->redirect()->toRoute('novidades');
            	}
            }
        } catch (SessaoMortaException  $e){
   			$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
        	return $this->redirect()->toRoute('login');
        }
        $retorno['form'] = $form;
        return new ViewModel($retorno);
    }
    
    /**
     * Action que adiciona uma dica
     */    
    public function adicionarDicaAction()
    {
        
        $retorno = array();
   	    try{
   	        $this->getModel()->verificaSessao();
            $form    = $this->getForm('adicionarDica');
            $request = $this->getRequest();
            if($request->isPost())
            {
            	$form->setData($request->getPost()->toArray());
            	
            	if ($form->isValid()) {
                	    $tipoConteudo = $this->getModel()->getTipoConteudo('dica');
                	    $this->getModel()->verificaSessao();
                	    $usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
                	    $data = $form->getData();
                	    FacebookNoticias::factory()->sendConteudo($tipoConteudo->tipo, $data['titulo'], strip_tags($data['conteudo']));
                	    $this->getModel()->salvarConteudo($form, $tipoConteudo->id_tipo_conteudo, $usuario);
                        $this->getModel('acaoUsuario','user')->pontoDica($usuario);
                        return $this->json('Dica adicionada com sucesso!');
            	} else {
            	    return $this->json($form->getErrorMessages(), false); 
            	}
            }
        } catch (SessaoMortaException  $e){
            return $this->json($e->getMessage(), false);
        }
        return $this->json('Não foi possível enviar a dica, tente mais tarde.', false);
    }
    
    /**
     * Action que adiciona um artigo
     */    
    public function adicionarArtigoAction()
    {
        $retorno = array();
   	    try{
   	        $this->getModel()->verificaSessao();
            $form    = $this->getForm('adicionarDica');
            $request = $this->getRequest();
            if($request->isPost())
            {
            	$form->setData($request->getPost()->toArray());
            	if ($form->isValid()) {
                	    $tipoConteudo = $this->getModel()->getTipoConteudo('artigo');
                	    $this->getModel()->verificaSessao();
                	    $usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
                	    $data = $form->getData();
                	    FacebookNoticias::factory()->sendConteudo($tipoConteudo->tipo, $data['titulo'], strip_tags($data['conteudo']));
                	    $this->getModel()->salvarConteudo($form, $tipoConteudo->id_tipo_conteudo, $usuario);
                	    $this->getModel('acaoUsuario','user')->pontoArtigo($usuario);
               			$this->flashMessenger()->addMessage($this->getMsg('Artigo adicionado com sucesso!'));
                        return $this->redirect()->toRoute('novidades');
            	}
            }
        } catch (SessaoMortaException  $e){
   			$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
        	return $this->redirect()->toRoute('login');
        }
        $retorno['form'] = $form;
        return new ViewModel($retorno);
    }
    
    /**
     * Action que deleta um conteúdo
     */
    public function deletarConteudoAction()
    {
   	    try{
   	        // verificando se o usuário informado do conteudo é igual ao usuário logado
            $this->getModel()->verificarUsuario($this->params('user'));
            
            // verificando se o conteúdo ainda existe no captura.me
            $this->getModel()->verificarConteudoExistente($this->params('conteudo'));
            
            // deletando o conteúdo
            $this->getModel()->deletarConteudo($this->params('user'), $this->params('conteudo'));
            
   			$this->flashMessenger()->addMessage($this->getMsg('Removido com sucesso!'));
            return $this->redirect()->toRoute('dicas');
           
        } catch (\Exception  $e){
   			$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
            return $this->redirect()->toRoute('dicas');
        }
    }

}
