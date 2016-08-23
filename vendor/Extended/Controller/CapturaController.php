<?php
namespace Extended\Controller;

use Zend\View\Model\JsonModel;

use Zend\View\Variables;

use Zend\Session\Container;

use User\Entity\User;

use User\Auth\CapturaAuth;

use User\Controller\Plugin\UserAuthentication;

use Common\Exception\SemPermissaoException;

use Extended\Object\ClassConfig;
use Zend\XmlRpc\Value\Integer;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Response as HttpResponse;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Exception;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DispatchableInterface as Dispatchable;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * Basic action controller
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller
 */
abstract class CapturaController extends AbstractActionController 
{
    
    protected $_model = null;
    protected $_forms = array();
    private $_jsonFlag = false;
    
    protected $_user = null;
    protected $_class = null;
    protected $_session = null;
    
    /**
     * Método que seta o usuário.
     * @param CapturaAuth $user Objeto do tipo de usuário
     * do portal.
     */
    public function setUser(CapturaAuth $user)
    {
        $this->_user = $user;
    }
    
    /**
     * Método que retorna qual é o usuário.
     * @return User Usuário setado no controlador.
     */
    public function getUser()
    {
        return $this->_user;    
    }
    
    /**
     * Sobrescrita do método onDispatch para controle de ações defaults
     * do CapturaController.
     * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
     */
    public function onDispatch(MvcEvent $e)
    {
        try
        {
            $this->preDispatch($e);
            $action = parent::onDispatch($this->getEvent());
            $this->postDispatch($e);
            
            if($this->_user->getUser() && $this->_user->getUser()->getBatalhando() && !$this->_jsonFlag){
            	$this->layout('layout/batalhandolayout.phtml');
            	if(!preg_match('/\/cadastrar-capturada-batalha\//', $e->getRouter()->getRequestUri()->getPath())){
            	    return $this->redirect()->toRoute("cadastrar-capturada-batalha", array('user' => $this->_user->getUser()->id_usuario, 'pagina' => '1'));
            	}
            }
            
            return $action;
            
        } catch(SemPermissaoException $semPermissao){
             $this->flashMessenger()->addMessage($this->getMsg($semPermissao->getMessage(), false));
             return $this->redirect()->toRoute('home');
             throw $semPermissao;
        }
    }
    
    /**
     * Conjunto de ações a serem realizadas antes de uma action do controlador.
     */
    public function preDispatch(MvcEvent $e)
    {
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendScript('var batalhas = null;');
        $this->_session = new Container('capturasession');
        $this->_user ?: $this->_user = CapturaAuth::getInstance();
        $class = get_called_class();
        $this->_class ?: $this->_class = ClassConfig::att(new $class);
        UserAuthentication::getInstance()->setEvent($e)->setCalledClass($this->_class)->doAuthorization($this->_user, $this->_class);
    }
    
    /**
     * Conjunto de ações a serem realizadas depois de uma action do controlador.
     */
    public function postDispatch(MvcEvent $e)
    {
        $this->_jsonFlag ?: $this->viewConfig($e);
        if(CapturaAuth::getInstance()->exists()){
            $this->atualizaDadosUsuario();
        }
    }
    
    /**
     * Método que verifica se modificou algum dado do usuário e faz o devido update
     * na sessão do usuário logado.
     */
    public function atualizaDadosUsuario()
    {
        $batalhando = CapturaAuth::getInstance()->getUser()->getBatalhando();
        CapturaAuth::getInstance()->setUser($this->getModel('user','user')->getUsuario(CapturaAuth::getInstance()->getUser()->id_usuario));
        $batalhando ? CapturaAuth::getInstance()->getUser()->ligarModoBatalha() : CapturaAuth::getInstance()->getUser()->desligarModoBatalha();
    }
    
    /**
     * Método que constroi um modelo de controlador.
     */
    public function __construct() 
    {
        
    }
    
    public function getViewHelper($helper)
    {
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helper);
    }
    
    /**
     * Método que retorna qual seria o model em questão.
     * @return \Extended\Model\CapturaModel
     */
    public function getModel($model = null, $modulo = null)
    {
    	$moduloUtilizado = $modulo ? ucfirst($modulo) :  ClassConfig::att($this)->module ;
    	$nomeModel =  $model ? ucfirst($model) : ClassConfig::att($this)->name ;

        if($model || $this->_model == null){
            $nameModelClass = '\\' . $moduloUtilizado . '\\Model\\' . $nomeModel . 'Model';
            $this->_model = new $nameModelClass();
            $this->_model->setServiceManager($this->getServiceLocator());
        }
        return $this->_model;
    }
    
    /**
     * Método que executa a ação de mergiar as
     * configurações padrões do controller do captura.
     */
    private function viewConfig(MvcEvent $e)
    {
        $variables = array(
            'module' =>  ClassConfig::att($this)->module,
            'controller' => ClassConfig::att($this)->name,
            'namecontroller' => ClassConfig::att($this)->classname,
            'action' => $this->getEvent()->getRouteMatch()->getParam('action'),
            'msg' => $this->flashMessenger()->getCurrentMessages(),
            'files' => count($this->getFiles()) == 1 ? current($this->getFiles()) : $this->getFiles(),
            'post' => $this->getRequest()->getPost(),
            'request' => $this->getRequest(),
            'url' => $url = $e->getRouter()->getRequestUri()->getScheme() . '://' . $e->getRouter()->getRequestUri()->getHost() . $e->getRouter()->getRequestUri()->getPath(),
            'indicacaoCapturada' => $this->params('capturada'),
            'uri' => $e->getRouter()->getRequestUri()->getPath(),
            'baseUrl' => array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '',      
        );
        
        $e->getViewModel()->url = $e->getRouter()->getRequestUri()->getScheme() . '://' . $e->getRouter()->getRequestUri()->getHost() . $e->getRouter()->getRequestUri()->getPath();
        $e->getViewModel()->class = ClassConfig::att($this);
        $e->getViewModel()->action = $this->getEvent()->getRouteMatch()->getParam('action');
        $e->getViewModel()->msg = $this->flashMessenger()->getCurrentMessages();
        $e->getViewModel()->hasMessages = $this->flashMessenger()->hasMessages();
        $e->getViewModel()->indicacaoCapturada = $this->params('capturada');
        $e->getViewModel()->uri = $e->getRouter()->getRequestUri()->getPath();
        $e->getViewModel()->baseUrl = array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER['HTTP_REFERER'] : '';

        if ($e->getResult() instanceof \zend\view\Model\ViewModel) {
            $prevContent = count($e->getResult()->getVariables()) > 0 ? $e->getResult()->getVariables() : array();
            $e->getResult()->setVariables(array_merge($prevContent, $variables));
        } else {
            $e->setResult(new ViewModel($variables));
        }
        
    }
    
    /**
     * Método que retorna em forma de json.
     * @param unknown_type $value valor a ser retornado
     * @param unknown_type $sucess
     */
    public function json($value, $sucess = true)
    {
        $this->_jsonFlag = true;
        return new \Zend\View\Model\JsonModel(array(
            'data' => $value,
            'success' => $sucess,
        ));
    }
    
    /**
     * Método que retorna uma renderização de html.
     * @param String $nameRender nome do arquivo a ser renderizado
     * @param array $variables conjunto de variáveis a serem utilizadas no arquivo a ser renderizado
     * @param String $module Nome do módulo onde está o arquivo renderizado
     * @param String $controller Nome do controlador a ser urilizado
     * @return string Html renderizado em formato de uma String.
     */
    public function renderPartial($nameRender, $variables = null, $module = null, $controller = null)
    {
    	// get the obj configurations
    	$module = $module == null ? ClassConfig::att($this)->module : $module;
    	$controller = $controller == null ?  ClassConfig::att($this)->name : $controller;
    	$nameToRender = $controller . '-' . $nameRender;
    	
    	// creating thr renderer
    	$renderer = new \Zend\View\Renderer\PhpRenderer();
    
    	// creating pointer of the phtml archive
    	$resolver = new \Zend\View\Resolver\TemplateMapResolver(
    	    new \Zend\View\Resolver\TemplateMapResolver(array(
    	        $nameToRender    => constant('VIEW') . strtolower("{$module}/{$controller}/{$nameRender}.phtml"),
    	    ))
        );
    
    	// Informing the resolver
    	$renderer->setResolver($resolver);
    
    	// creating the view model
    	$model = $variables != null && is_array($variables) ? new ViewModel($variables) : new ViewModel();
    
    	// Informe the template
    	$model->setTemplate($nameToRender);
    
    	return $renderer->render($model);
    }
    
    /**
     * Método que retorna o que vem no envio de arquivos.
     * @return Array Array de parâmetros dos arquivos enviados.
     */
    public function getFiles($nome = null)
    {
        return $_FILES 
            ? array_key_exists($nome, $_FILES) 
                ? $_FILES[$nome] : $_FILES
            : null;
    }
    
    /**
     * Método que retorna um padrão para as mensagens no site captura.me.
     * @param String $msg conteúdo a ser impresso na mensagem 
     * @param Boolean $status status se foi um sucesso = true, se for uma falha = false.
     * @return StdClass
     */
    public function getMsg($msg, $status = 1)
    {
        return (object) array(
            'status' => (int)$status,
            'msg' => $msg
        );
    }
    
    /**
     * Método que captura um formulário.
     * @param String $nome nome do formulário a ser carregado.
     * @return \Zend\Form\Form formulário em questão.
     */
    public function getForm($nome)
    {
    	if(!array_key_exists($nome, $this->_forms)){
    		$formName = ClassConfig::att($this)->getPath('form', $nome);
    		$this->setForm($nome, new $formName());
    	}
    	return $this->_forms[$nome];
    }
    
    /**
     * Método que seta o valor na pilha de formularios.
     * @param unknown_type $nome
     * @param Form $registerForm
     */
    public function setForm($nome, \Zend\Form\Form $registerForm)
    {
    	$this->_forms[$nome] = $registerForm;
    }
}