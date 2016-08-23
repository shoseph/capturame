<?php

namespace User\Entity;

class ClassConfig 
{

    protected static $_instance = null;
    protected $_moduleName;
    protected $_classFolder;
    protected $_className;
    protected $_controllerName;
    private static $_trace;

    /**
     * Método que preenche os itens referentes a classe.
     */
    private function setItens()
    {
        if (isset(self::$_trace[1])) {
        	$file = explode('/', self::$_trace[1]['file']);
        	$len = count($file);
        	$this->_moduleName = $file[$len-3];
        	$this->_classFolder = $file[$len-2];
        	$this->_className = current(explode('.',$file[$len-1]));
        } else {
        	list($this->_moduleName, $this->_classFolder, $this->_className) = explode('\\', get_called_class());
        }
       	$this->_controllerName = current(explode('Controller',$this->_className));
    }
    
    // Um construtor privado
    private function __construct()
    {  
        $this->setItens();    
    }
    
    // O método singleton
    public static function att()
    {
        
    	if (!isset(self::$_instance)) {
            self::$_trace = debug_backtrace();
    		$c = __CLASS__;
    		self::$_instance = new $c();
    	}
    	return self::$_instance;
    }
    
	
    public function __get($name)
    {
        $nome = $name[0] == '_' ? $name : "_{$name}";
        return $this->$nome;
    }
    
    public function getJsPath()
    {
        $filter = new \Zend\Filter\Word\CamelCaseToDash();
        return strtolower($filter->filter($this->_moduleName)) . '/' . strtolower($this->_controllerName) . '/';
    }
    
    public function getPath($pasta, $nome)
    {
        return '\\' . $this->_moduleName . '\\' . ucfirst($pasta). '\\' . ucfirst($nome) . ucfirst($pasta);
    }

}
