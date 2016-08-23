<?php
namespace User\Controller;

use Facebook\src\Facebook;

use Zend\Session\Container;

use Common\Exception\EmailInvalidoException;

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
class UserController extends CapturaController
{
    
    public function indexAction()
    {
        $retorno['nome'] = ucfirst(CapturaAuth::getInstance()->getUser()->nome);

        //TODO: verificar como pegar o tipo de usuário
        $retorno['titulo'] = 'Capturador: ';
        $retorno['quantidadeCapturadas'] = $this->getModel('user','user')->getQuantidadeCapturadas(CapturaAuth::getInstance()->getUser()->id_usuario);
        $retorno['batalhas'] = $this->getModel('batalha','capturada')->getBatalhasAtuais();
        $retorno['usuario'] = $this->getModel('user','user')->getUsuario(CapturaAuth::getInstance()->getUser()->id_usuario);
        return new ViewModel($retorno);
           
    }
    
    /**
     * Action que faz o devido login do usuário.
     */
    public function loginAction()
    {
        
        if(CapturaAuth::exists()){
            return $this->redirect()->toRoute('user-index', array('user'=> CapturaAuth::getUser()->id_usuario) );
        }
        $request = $this->getRequest();
        $form    = $this->getForm('login');
        if($this->getRequest()->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $capturador = $this->getModel()->autenticar($form);
                    CapturaAuth::getInstance()->setUser($capturador);
                    $this->_session->offsetUnset('form');
                    return $this->redirect()->toRoute('home');
                } catch (UsuarioInativoException $u){
                    $this->flashMessenger()->addMessage($this->getMsg($u->getMessage(), false));
                } catch (UsuarioInvalidoException $u){
                    $this->flashMessenger()->addMessage($this->getMsg($u->getMessage(), false));
                }
            } else {
                $this->flashMessenger()->addMessage($this->getMsg($form->getStringErrorMessages(), false));
            }
        } 
        !$form->hasValidated() ?: $this->_session->offsetSet('form', $form->getData()) ;
        return $this->redirect()->toRoute('home');
    }
    
    
    /**
     * Action que faz a ação de esqueci senha
     */
    public function modificarSenhaAction()
    {
        if(!CapturaAuth::exists()){
            return $this->redirect()->toRoute('home');
        }
        $request = $this->getRequest();
        $form    = $this->getForm('modificarSenha');
        if($this->getRequest()->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    
                    $capturador = $this->getModel()->modificarSenha($form);
                    $this->flashMessenger()->addMessage($this->getMsg('Senha modificada com sucesso!', true));
                    return $this->redirect()->toRoute('home');
                } catch (SessaoMortaException $u){
                    $this->flashMessenger()->addMessage($this->getMsg($u->getMessage(), false));
                    return $this->redirect()->toRoute('home');
                }
            }
        } 
        return new ViewModel(array('form' => $form));
    }
    
    
    /**
     * Action que faz a ação de esqueci senha
     */
    public function esqueceuSenhaAction()
    {
        if(CapturaAuth::exists()){
            return $this->redirect()->toRoute('user-index', array('user'=> CapturaAuth::getUser()->id_usuario) );
        }
        
        $request = $this->getRequest();
        $form    = $this->getForm('esqueceuSenha');
        
        if($this->getRequest()->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    
                    $capturador = $this->getModel()->esqueceuSenha($form);
                    $this->flashMessenger()->addMessage($this->getMsg('Verifique seu email para ver a nova senha gerada.', true));
                    return $this->redirect()->toRoute('home');
                } catch (EmailInvalidoException $u){
                    $this->flashMessenger()->addMessage($this->getMsg($u->getMessage(), false));
                } 
            }
        } 
        return new ViewModel(array('form' => $form));
    }
    
    /**
     * Action que desloga o usuário.
     */
    public function logoutAction()
    {
        $facebook = new Facebook(array(
        		'appId' => '662887060405967',
        		'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
        		'cookie' => 'true',
        ));
        if($facebook->getUser()){
            $facebook->destroySession();
        }
        CapturaAuth::getInstance()->logout();
        
        return $this->redirect()->toRoute('login');
    }
    
    /**
     * Action que valida o usuário cadastrado no captura.me
     */
    public function validarAction()
    {
        $user = $this->getModel()->findInativo(
                preg_replace('/[.-]/', '', base64_decode($this->params('hash')))
        );
        if($user->count()){
            // existe usuário inativo, logo ative e envie a mensagem que foi ativado com sucesso
            $this->getModel()->ativarUsuario($user);
            $this->flashMessenger()->addMessage($this->getMsg('Usuário ativado!'));
        } else{
            $this->flashMessenger()->addMessage($this->getMsg('Usuário já ativado',false));
        }  
    }
    
    /**
     * Action que registra um usuário.
     */
    public function registrarAction()
    {
        
        // TODO: possível capturar com uma variável no pre-dispatch
        $request = $this->getRequest();
        
        // TODO: possível capturar pelo nome da action
        $form    = $this->getForm('registrar');
        
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		try{
        		    $user = User::getInstance()->fillInArray($form->getData());
        		    $this->getModel()->verificarExistente($user);
            	    $idUser = $this->getModel()->salvar($form);
            	    $this->getModel()->emailCadastro($form, $idUser);
              		$this->flashMessenger()->addMessage($this->getMsg('Cadastrado com sucesso, verifique seu email para ativação do usuário!'));
                    
              		// caso utilizar o facebook para preencher os dados
              		$facebook = new Facebook(array('appId' => '662887060405967',  'secret' => 'b99bc88aefabe5ba9464968ce87d193e', 'cookie' => 'true'));
              		$facebook->destroySession();
              		
                    return $this->redirect()->toRoute('home');
                    
        		} catch (LoginExistenteException $e){
             		$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
        		} catch (CpfExistenteException $e){
             		$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
        		} catch (\Exception $e){
             		$this->flashMessenger()->addMessage($this->getMsg('Impossível cadastrar este usuário',false));
        		}
        	} 
        }
        return new ViewModel(array('form' => $form));
    }
    
    /**
     * Action que registra um usuário.
     */
    public function editarUsuarioAction()
    {
        $request = $this->getRequest();
        
        // TODO: possível capturar pelo nome da action
        $form    = $this->getForm('editarUsuario');
        
        if ($request->isPost()) {
            	    
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		try{
        		    $user = $this->getModel('user','user')->getUsuario(CapturaAuth::getInstance()->getUser()->id_usuario);
        		    $user->updateByArray($form->getData());
            	    $this->getModel()->atualizar($user);
             		$this->flashMessenger()->addMessage($this->getMsg('Seus dados foram atualizados com sucesso!'));
                    return $this->redirect()->toRoute('home');
        		} catch (CpfExistenteException $e){
             		$this->flashMessenger()->addMessage($this->getMsg($e->getMessage(),false));
        		} catch (\Exception $e){
             		$this->flashMessenger()->addMessage($this->getMsg('Não foi possível editar seus dados, tente mais tarde!',false));
        		}
        	} else {
                $this->flashMessenger()->addMessage($this->getMsg($form->getStringErrorMessages(), false));
            } 
        } else {
            $usuario = $this->getModel('user','user')->getUsuario(CapturaAuth::getInstance()->getUser()->id_usuario);
            $form->setData($usuario->toArray());
            
        }
        return new ViewModel(array('form' => $form));
    }

}
