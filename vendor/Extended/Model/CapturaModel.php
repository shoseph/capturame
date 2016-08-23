<?php
namespace Extended\Model;
use Extended\Object\ClassConfig;

use ZendGData\EXIF\Extension\Model;

use User\Auth\CapturaAuth;

use Zend\Db\TableGateway\TableGateway;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceManager as ServiceManager;

use Zend\Db\TableGateway\Feature as Feature;
/**
 * Basic action module
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller
 */
abstract class CapturaModel implements InputFilterAwareInterface
{
    
    private $_table;
    private $_serviceManager;
    private $_config;
    
    
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
    		$this->_model->setServiceManager($this->getServiceManager());
    	}
    	return $this->_model;
    }
    
    /**
     * Método que retorna as propriedades da classe instanciadora
     */
    private function setConfig() {
    	preg_match('/(?P<modulename>[A-Za-z]+)\\\Model\\\(?P<classname>[a-zA-Z]+)Model/', get_called_class(), $infoClass);
    	$this->_config = (object) array('module' => $infoClass['modulename'], 'class' => $infoClass['classname']);
    }
    
    /**
     * Método que constroi um modelo do captura.me
     * @param \stdClass $config
     */
    public function __construct(){
        $this->setConfig();
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Não utilizado");
    }
    
    public function getInputFilter(){
        throw new \Exception("Não utilizado");
    }
    
    /**
     * Método que recebe o objeto que permite instanciar uma table
     * @param ServiceLocatorInterface $sm
     */
    public function setServiceManager(ServiceManager $sm){
        $this->_serviceManager = $sm;
    }
   
    /**
     * Método que retorna o service manager.
     */
    public function getServiceManager(){
    	return $this->_serviceManager;
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
     * Método que retorna uma instancia de uma table
     * @param unknown_type $table
     */
    public function getTable($table = null, $modulo = null)
    {
        $moduloUtilizado = $modulo ? ucfirst($modulo) : $this->_config->module;
        $nomeTable =  $table ? ucfirst($table) : $this->_config->module;
        if ($table != null || !$this->_table) {
            $path = "{$moduloUtilizado}\\Table\\{$nomeTable}Table";
            $this->_table = new $path(
                $this->getServiceManager()->get('Zend\Db\Adapter\Adapter'), $nomeTable
            );
        }
        return $this->_table;
    }
   
}
