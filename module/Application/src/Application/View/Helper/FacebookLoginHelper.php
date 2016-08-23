<?php
namespace Application\View\Helper;

use User\Auth\CapturaAuth;

use User\Entity\User;

use Facebook\src\FacebookApiException;

use Facebook\src\Facebook;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class FacebookLoginHelper extends CapturaViewHelper
{
    protected $_config;
    protected $_form;

    public function __invoke ()
    {
        $this->_config = array('me' => null, 'vinculado' => false);
        $this->_config['view'] = $this->getView();
        $facebook = new Facebook(array(
        	'appId' => '662887060405967',
        	'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
        	'cookie' => 'true',
        ));
        if ($this->_config['me']) {
        	$this->_config['logoutUrl'] = $facebook->getLogoutUrl();
        } else {
        	$this->_config['loginUrl'] = $facebook->getLoginUrl(array('scope' => 'email, publish_actions, user_about_me', 'redirect_uri'=> "http://{$_SERVER['HTTP_HOST']}/facebook-login/1"));
        }  
        return $this->renderPartial('facebook-login', $this->_config, 'Application', 'Index');
    }
            
}