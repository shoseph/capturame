<?php
namespace User\Controller;

use Facebook\src\FacebookApiException;

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
class FacebookController extends CapturaController
{
    
    public function facebookLoginAction()
    {
        $facebook = new Facebook(array(
        	'appId' => '662887060405967',
        	'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
        	'cookie' => 'true',
        ));
        $idUserFacebook = $facebook->getUser();
        if(!$idUserFacebook){
            $this->flashMessenger()->addMessage($this->getMsg('Você não está logado no facebook!', false));
            return $this->redirect()->toRoute('home');
        }
        
        try {
       		$facebook->setExtendedAccessToken();
   	    	$token = $facebook->getAccessToken();
   		    $me = $facebook->api('/me');
        } catch (FacebookApiException $e) {
            $this->flashMessenger()->addMessage($this->getMsg('Facebook não conseguiu achar seu usuário.', false));
            return $this->redirect()->toRoute('home');
        }
        
   		$user = $this->getModel('User', 'User')->getUser(User::getInstance()->fillInArray(array('id_facebook' => $me['id'])));
   		$user->id_usuario ? CapturaAuth::getInstance()->setUser($user) : $this->flashMessenger()->addMessage($this->getMsg('Você ainda não viculou sua conta do Captura.Me com o Facebook, logue e vincule!', false));
        
        try {
            if($this->params('publicar'))
            {
       	        $args = array('link'  => "http://captura.me/novas-capturadas/1/" . rand(1,12),'access_token' => $token,'message'      => "Eu estou no captura.me, vendo belas imagens e você?\n FacebookPage: https://www.facebook.com/capturame\n Twitter: http://twitter.captura.me\n\n #fotos #diversão #shows #capturadas #novidades #protestos #joaopessoa #joãopessoa ",);
       	        $facebook->api("/{$idUserFacebook}/links", 'POST',$args);
        	}	
        } catch (FacebookApiException $e) { 
            // TODO: verificar como se faz para adicionar a permissão caso ele conceda com o botão publicar 
        }

        return $this->redirect()->toRoute('home');
        
    }
    
    public function vincularFacebookCapturameAction()
    {
        $facebook = new Facebook(array(
        	'appId' => '662887060405967',
        	'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
        	'cookie' => 'true',
        ));
        $user = $facebook->getUser();
        if ($user = $facebook->getUser()) {
        	try {
        		$facebook->setExtendedAccessToken();
        		$token = $facebook->getAccessToken();
        		$me = $facebook->api('/me');
        		$user = $this->getModel('User', 'User')->getUsuario(CapturaAuth::getInstance()->getUser()->id_usuario);
        		$user->id_facebook = $me['id'];
        		$this->getModel('User', 'User')->atualizar($user);
        		CapturaAuth::getInstance()->setUser($user);
        		$facebook->destroySession();
                $this->flashMessenger()->addMessage($this->getMsg('Seu facebook foi vinculado com sucesso!', true));
        		$this->redirect()->toRoute('home');
        		       
        	} catch (FacebookApiException $e) {
                $this->flashMessenger()->addMessage($this->getMsg('Erro ao acessar pelo usuário do facebook, tente logar com usuário e senha.', false));
        		error_log($e);
                $this->redirect()->toRoute('home');
        	}
        }
    }

}
