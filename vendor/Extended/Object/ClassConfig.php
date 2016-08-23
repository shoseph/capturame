<?php

namespace Extended\Object;

use \Extended\Controller\CapturaController;

class ClassConfig 
{
    protected $_module;
    protected $_name;
    protected $_classname;
    protected $_type;

    protected static $_instance = null;
    protected static $_obj;
    
        
    /**
     * Método que preenche os itens referentes a classe.
     */
    private function setItens()
    {
        $result = explode('\\', self::$_obj);
        $reflector = new \ReflectionClass(self::$_obj);
        preg_match('/(?P<name>[A-Z][a-z]+)(?P<type>[A-Z][a-z]+)/', $result[2], $class);
        $this->_module = current(explode('\\', $reflector->getNamespaceName()));
        $this->_classname = $result[2];
        $this->_name = $class['name'];
        $this->_type = $class['type'];
    }
    
    // Um construtor privado
    private function __construct()
    {  
        $this->setItens();
    }
    
    // O método singleton
    public static function att($obj)
    {
  	    self::$_obj = get_class($obj); 
    	if (!isset(self::$_instance)) {
            $c = __CLASS__;
    		self::$_instance = new $c();
    	}
        
    	return self::$_instance;
    }
    
	/**
	 * Implementando o método get para acesso dos itens
	 * de forma rapida.
	 * @param String $name nome da variável.
	 */
    public function __get($name)
    {
        $this->setItens();
        $nome = $name[0] == '_' ? $name : "_{$name}";
        return $this->$nome;
    }
    
    /**
     * Método que retorna como deve ser o path de arquivos js do objeto atual.
     * @return string
     */
    public function getJsPath()
    {
        $filter = new \Zend\Filter\Word\CamelCaseToDash();
        return strtolower($filter->filter($this->_module)) . '/' . strtolower($this->_name) . '/';
    }
    
    /**
     * Método que retorna o caminho para o objeto que é carregado 
     * na pasta($pasta) do modulo em questão.
     * @param String $pasta nome e ao mesmo tempo tipo do objeto.
     * @param String $nome nome do objeto.
     * @return string path mais nome do objeto para que seja possível
     * instancia-lo.
     */
    public function getPath($pasta, $nome)
    {
        return '\\' . $this->_module . '\\' . ucfirst($pasta). '\\' . ucfirst($nome) . ucfirst($pasta);
    }

}
