<?php
namespace Extended\Form;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
/**
 * Basic action form
 */
abstract class CapturaForm extends Form
{
    protected $_inputFilter;
    protected $_factory;
    
    /**
     * Sobrescrita do método onDispatch para controle de ações defaults
     * do CapturaController.
     * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
     */
    public function onDispatch(MvcEvent $e)
    {
    	$this->preDispatch($e);
    	$action = parent::onDispatch($this->getEvent());
    	$this->postDispatch($e);
    
    	return $action;
    }
    
    /**
     * Conjunto de ações a serem realizadas antes de uma action do controlador.
     */
    public function preDispatch(MvcEvent $e)
    {
    
    }
    
    /**
     * Conjunto de ações a serem realizadas depois de uma action do controlador.
     */
    public function postDispatch(MvcEvent $e)
    {
    }
    
    
    /**
     * Método que seta o metodo do form para $type
     * @param String $type Post ou Get
     */
    public function getFormAttributes($type = 'post')
    {
        $this->setAttribute('method', $type);
    }
    
    /**
     * Método que precisa ser criado para obter o nome do formulário
     */
    public abstract function getFormName();
    
    /**
     * Método que precisa ser criado para obter os campos do formulário
     */
    public abstract function createFormFiled();
    
    /**
     * Método que precisa ser criado para obter os filtros do formulário
     */
    public abstract function createFilters();
    
    /**
     * Método que constroi um modelo de controlador.
     */
    public function __construct()
    {
        parent::__construct($this->getFormName());
        $this->_inputFilter = new InputFilter();
        $this->_factory = new InputFactory();
        $this->getFormAttributes();
        $this->createFormFiled();
        $this->createFilters();
        $this->setInputFilter($this->_inputFilter);
    }
    
    public function getErrorMessages(){
        $messages = array();
        $errors = $this->getMessages();
        foreach($errors as $key=>$row)
        {
        	if (!empty($row) && $key != 'submit') {
        		foreach($row as $keyer => $rower)
        		{
        			$messages[$key][] = $rower;
        		}
        	}
        }
        return $messages;            
    }
    
    public function getStringErrorMessages(){
        $messages = '';
        $errors = $this->getMessages();
        foreach($errors as $key=>$row)
        {
        	if (!empty($row) && $key != 'submit') {
        		foreach($row as $keyer => $rower)
        		{
        			$messages .= "[{$key}] {$rower} <bt />";
        		}
        	}
        }
        return $messages;            
    }
      
}
