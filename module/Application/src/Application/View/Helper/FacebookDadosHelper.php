<?php
namespace Application\View\Helper;

use Facebook\src\FacebookApiException;

use Facebook\src\Facebook;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class FacebookDadosHelper extends CapturaViewHelper
{
    protected $_config;
    protected $_form;

    public function __invoke ()
    {
        return $this;
    }
    
    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }
    
    public function facebookData()
    {
        $this->_config = array('me' => null);
        $this->_config['view'] = $this->getView();
        $facebook = new Facebook(array(
        		'appId' => '662887060405967',
        		'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
        		'cookie' => 'true',
        ));
        if ($user = $facebook->getUser()) {
        	try {
        		$facebook->setExtendedAccessToken();
        		$token = $facebook->getAccessToken();
        		$this->_config['me'] = $me = $facebook->api('/me');
        
        		$this->_form->setData(array(
        				'id_facebook' => $me['id'],
        				'email' => $me['email'],
        				'login' => $me['username'],
        				'nome' => $me['name'],
        		));
        	} catch (FacebookApiException $e) {
//         		dump($e->getMessage(),1);
        		error_log($e);
        	}
        }
        if ($this->_config['me']) {
        	$this->_config['logoutUrl'] = $facebook->getLogoutUrl();
        } else {
        	$this->_config['loginUrl'] = $facebook->getLoginUrl(array('scope' => 'email, user_about_me'));
        }  
        return $this;  
    }
    
    public function html()
    {
        return $this->renderPartial('facebook-dados', $this->_config, 'Application', 'Index');
    }
    
}