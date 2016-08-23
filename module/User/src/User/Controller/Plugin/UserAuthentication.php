<?php
namespace User\Controller\Plugin;
use Common\Exception\SemPermissaoException;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
Zend\Session\Container as SessionContainer,
Zend\Permissions\Acl\Acl,
Zend\Permissions\Acl\Role\GenericRole as Role,
Zend\Permissions\Acl\Resource\GenericResource as Resource;

use User\Auth\CapturaAuth;
use Extended\Object\ClassConfig;
class UserAuthentication extends AbstractPlugin
{
    protected $_acl;
    protected $_config;
	protected $sesscontainer ;
	protected static $_event;
	protected static $_calledClass;
    protected static $_instance = null;
    protected static $_role = null;
    
    // Um construtor privado
    private function __construct()
    {  
        $this->_config = include_once constant('ROOT') . '/module/User/config/acl.config.php';
        $this->_acl = new Acl();
        $this->startAcl();
    }
    
    // O método singleton
    public static function getInstance()
    {
    	if (!isset(self::$_instance)) {
            $c = __CLASS__;
    		self::$_instance = new $c();
    	}
    	return self::$_instance;
    }
    
    /**
     * Método que faz o set do evento
     * @param MvcEvent $event objeto do mvc
     */
    public static function setEvent($event){
        UserAuthentication::$_event = $event;
    	return self::$_instance;
    }
    
    /**
     * Método que faz o set da classe que chama o user autentication
     * @param CapturaController $class objeto controlador
     */
    public static function setCalledClass($calledClass){
        UserAuthentication::$_calledClass = $calledClass;
    	return self::$_instance;
    }
	
	/**
	 * Capturando a sessão do usuário
	 */
	private function getSessContainer()
	{
		if (!$this->sesscontainer) {
			$this->sesscontainer = new SessionContainer('usuario');
		}
		return $this->sesscontainer;
	}

	/**
	 * Método que inicia a acl
	 */
	private function startAcl()
	{
	    // configure the roles
	    foreach($this->_config['roles'] as $role => $parentRole){
	        $this->_acl->addRole(new Role($role), $parentRole);
	    }
	    
	    // configure the resources
	    foreach($this->_config['resources'] as $resource){
	        $this->_acl->addResource(new Resource($resource));
	    }
	    
	    // configure the allow 
	    foreach($this->_config['allow'] as $role => $arrResource){
	        foreach ($arrResource as $resource => $privileges){
	            $this->_acl->allow($role, $resource, $privileges);
	        }
	    }
	    
	    // configure the deny 
	    foreach($this->_config['deny'] as $role => $arrResource){
	        foreach ($arrResource as $resource => $privileges){
	            $this->_acl->deny($role, $resource, $privileges);
	        }
	    }
	    
	}
	
	public function hasPermission($action)
	{
	    $resource = UserAuthentication::$_calledClass->module . UserAuthentication::$_calledClass->classname;
	    $role = CapturaAuth::getInstance()->getUser() ? CapturaAuth::getInstance()->getUser()->tipo : 'visitante';
	    return $this->_acl->isAllowed($role, $resource, $action);
	}
	
	/**
	 * Método que retorna se tem permissão de executa uma determinada ação
	 * caso não possua deve ser enviado para o /
	 * @param MVc $e
	 */
	public function doAuthorization($user, $class)
	{
	    $e = UserAuthentication::$_event;
	    $path = $e->getRouter()->getRequestUri()->getPath();
	    $resource = $class->module . $class->classname;
	    $action = $e->getRouteMatch()->getParam('action');
	    $role = $user->getUser() ? $user->getUser()->tipo : 'visitante';

	    // invalida a busca assim, pois quando chega em uma action 'index' não encontra
// 	    if($routeFinded = $e->getRouter()->getRoutes()->get($action)){
// 	        $inicialPath = $routeFinded->assemble();
// 	    }
	    if(!$this->_acl->isAllowed($role, $resource, $action)){
//  	    var_dump(array('exception'=> array($role, $resource, $action))); exit;
	        throw new SemPermissaoException();
	    } 
	}
}