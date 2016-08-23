<?php
namespace Extended\View;
use Zend\Session\Container;

use Zend\View\Model\ViewModel;

use Extended\Object\ClassConfig;

use Zend\Form\View\Helper\AbstractHelper;
/**
 * Classe que tem o básico de um view helper no captura.me
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller
 */
abstract class CapturaViewHelper extends AbstractHelper
{
    private $_table = null;
    private $_model = null;
    private $_sm = null;
    protected $_session = null;
    protected $_variables = null;
        
    public function __construct ($sm)
    {
        $this->_session = new Container('capturasession');
    	$this->_sm = $sm;
    }
    
    /**
     * Método que retorna as variáveis setadas no helper
     */
    public function getVariables()
    {
        return $this->_variables;    
    }
    
    /**
     * Método que retorna uma variável em específico do 
     * array de variáveis de um helper
     * @param String $name indice no array de variáveis
     * @return Variável no array de variáveis
     */
    public function getVariable($name)
    {
        return $this->_variables[$name];    
    }
    
    /**
     * Método que chama outro view helper
     * @param String $helper nome do viewhelper registrado
     */
    public function getViewHelper($helper)
    {
        return $this->_sm->getServiceLocator()->get('viewhelpermanager')->get($helper);
    }
    
    public function getTable($table = null, $modulo = null)
    {
    	$moduloUtilizado = $modulo ? ucfirst($modulo) : ClassConfig::att($this)->module;
    	$nomeTable =  $table ? ucfirst($table) : ClassConfig::att($this)->name;
    	if ($table != null || !$this->_table) {
    		$path = "{$moduloUtilizado}\\Table\\{$nomeTable}Table";
    		$this->_table = new $path(
    			$this->_sm->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
    		);
    	}
    	return $this->_table;
    }
    
    public function getModel($model = null, $modulo = null)
    {
    	$moduloUtilizado = $modulo ? ucfirst($modulo) :  ClassConfig::att($this)->module ;
    	$nomeModel =  $model ? ucfirst($model) : ClassConfig::att($this)->name ;

        if($model != null || !$this->_model){
    		$path = "{$moduloUtilizado}\\Model\\{$nomeModel}Model";
    		$this->_model = new $path(
    			$this->_sm->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
    		);
    		$this->_model->setServiceManager($this->_sm->getServiceLocator());
        }
        return $this->_model;
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
    
    	// getting the variables in viewHelper
    	$variables == null ?: $this->_variables = $variables; 
    	
    	// Informe the template
    	$model->setTemplate($nameToRender);
    
    	return $renderer->render($model);
    }
}
