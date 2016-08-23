<?php
namespace Extended\Table;
/**
 * Classe que tem o básico de um objeto espelho do banco
 * para o projeto captura.me
 *
 * @category Zend
 * @package Zend_Mvc
 * @subpackage Controller
 */
abstract class CapturaObject 
{
    private static $instance = null;
    
    /**
     * Método que retorna uma instancia singleton do
     * método que extend a classe CapturaObject
     */
    public static function getInstance()
    {
        if (CapturaObject::$instance === null || get_class(CapturaObject::$instance)!= get_called_class()) {
            $classe = '\\' .get_called_class();
        	CapturaObject::$instance = new $classe();
        }
        return CapturaObject::$instance;
    }
    
    /**
     * Método que retorna uma nova instancia obrigatoria
     * utilizado para se popular dados.
     */
    public static function getNewInstance()
    {
        $classe = '\\' .get_called_class();
     	CapturaObject::$instance = new $classe();
        return CapturaObject::$instance;
    }
    
    /**
     * Método statico de preencher o objeto em questão;
     * @param unknown_type $data
     */
    public static function fillInArray ($data)
    {
    	$refClass = new \ReflectionClass(get_called_class());
    	$properties = $refClass->getProperties();
    	foreach ($properties as $property) {
    		$doc = $property->getDocComment();
    		preg_match('/@name[\s]{0,}=[\s]{0,}[\'\"](?P<name>[\w_]*)[\'\"]/', $doc, $name);
    		preg_match('/@type[\s]{0,}=[\s]{0,}[\'\"](?P<type>[\w_]*)[\'\"]/', $doc, $type);
    		preg_match('/@exclude/', $doc, $exclude);
    		if(count($exclude) == 0){
    			$name = $property->name;
    			CapturaObject::$instance->$name = (isset($data[$property->name])) ? $data[$property->name] : null;
    		}
    	}
    	return CapturaObject::$instance;
    }
    
    public function exchangeArray ($data)
    {
        $refClass = new \ReflectionClass(get_called_class());
    	$properties = $refClass->getProperties();
    	foreach ($properties as $property) {
    	    $doc = $property->getDocComment();
    	    preg_match('/@name[\s]{0,}=[\s]{0,}[\'\"](?P<name>[\w_]*)[\'\"]/', $doc, $name);
    	    preg_match('/@type[\s]{0,}=[\s]{0,}[\'\"](?P<type>[\w_]*)[\'\"]/', $doc, $type);
    	    preg_match('/@exclude/', $doc, $exclude);
    	    if(count($exclude) == 0){
        		$name = $property->name;
        		$this->$name = (isset($data[$property->name])) ? $data[$property->name] : null;
    	    }
    	} 
    }
    
    public function updateByArray ($data)
    {
        $refClass = new \ReflectionClass(get_called_class());
    	$properties = $refClass->getProperties();
    	foreach ($properties as $property) {
    	    $doc = $property->getDocComment();
    	    preg_match('/@name[\s]{0,}=[\s]{0,}[\'\"](?P<name>[\w_]*)[\'\"]/', $doc, $name);
    	    preg_match('/@type[\s]{0,}=[\s]{0,}[\'\"](?P<type>[\w_]*)[\'\"]/', $doc, $type);
    	    preg_match('/@exclude/', $doc, $exclude);
    	    if(count($exclude) == 0 && isset($data[$property->name])){
        		$name = $property->name;
        		$this->$name = $data[$property->name];
    	    }
    	} 
    }

    /**
     * Método que seta as propriedades da classe instanciadora
     */
    private function getConfig()
    {
    	preg_match('/(?P<modulename>[A-Za-z]+)\\\Table\\\(?P<classname>[a-zA-Z]+)/', get_called_class(), $infoClass);
    	return (object) array('module' => $infoClass['modulename'], 'class' => $infoClass['classname']);
    }
    
    /**
     * Set a static property
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     * @see   http://php.net/manual/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
        return CapturaObject::$instance;
    }
    
    /**
     * Get a static property
     *
     * @param  string $name
     * @return mixed
     * @see    http://php.net/manual/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __get($name)
    {
        return $this->{$name};
    }
    /**
     * Método que por reflexão retorna se existe um método dentro de uma
     * determinada classe informada no segundo parâmetro.
     * 
     * @param String $metodo Nome do método dentro da classe.
     * @param String $classe caminho completo até a classe.
     * ex: Capturada\Table\Capturada
     */
    public function existeMetodo($metodo, $classe)
    {
        $ref = new \ReflectionObject(new $classe);
        if(!$ref->hasMethod($metodo)){
            try { 
                preg_match('/(?P<prefix>get|set)(?P<variable>[A-Z][\w0-9]+)/', $metodo, $itens);
                if(array_key_exists('prefix', $itens) && $ref->getMethod('__call') ){
                    return true;
                }
                $ref->getMethod($metodo);
                 
            } catch(\Exception $e) { 
                return false;
            }
        }
        return true;
    }
    
    public function __call($method, $arguments)
    {
        if(!$this->existeMetodo($method, get_called_class())){
            throw new \Exception("Método <b>{$method}</b> inexistente na classe <b>{$this->getConfig()->class}</b>");
        }
        
        preg_match('/(?P<prefix>get|set)(?P<variable>[A-Z][\w0-9]+)/', $method, $itens);
        if(count($itens) > 0 && array_key_exists('prefix', $itens)){
            $variavel = lcfirst($itens['variable']);
            switch($itens['prefix']){
                // Implementação de um set
                
                case 'set':
                    $this->{$variavel} = current($arguments);
                    return CapturaObject::$instance; 

                break;
                
                // Implementação de um get
                case 'get': return $this->$variavel; break;
            }
        } else {
            $this->$method($arguments);
        }
    }
    
    /**
     * Método que monta as configurações de um phpDoc de um objeto row
     * @param String $stringPhpDoc String retirada do phpDoc
     * @return \stdClass Objeto com as configurações do phpDoc da coluna.
     */
    public function getRowConfig($stringPhpDoc)
    {
        preg_match('/@ORM\\\[A-Za-z]+\((?P<column>[\w=\'\"\,\s]+)\)/', $stringPhpDoc, $return);
        
        if(!$return['column'])
            return null;
        
        $return = '{' . preg_replace(array('/([\w]+)=/','/\'/'), array('"$1":','"'), $return['column']) .'}';
        return json_decode($return);
    }
    
    /**
     * Método que transforma um objeto em um array.
     * @return array:
     */
    public function toArray($withNull = false)
    {
        $retorno = array();
        $refClass = new \ReflectionClass(get_called_class());
        $properties = $refClass->getProperties();
        foreach ($properties as $property) {
        	$doc = $property->getDocComment();
        	preg_match('/@name[\s]{0,}=[\s]{0,}[\'\"](?P<name>[\w_]*)[\'\"]/', $doc, $name);
        	preg_match('/@type[\s]{0,}=[\s]{0,}[\'\"](?P<type>[\w_]*)[\'\"]/', $doc, $type);
        	preg_match('/@exclude/', $doc, $exclude);
        	if(count($exclude) == 0){
        		$nameProp = $property->name;
    			$retorno[$name['name']] = $this->$nameProp;
          	}
        }
    	return  $withNull ? $retorno : array_filter($retorno, 'strlen');
    }
    
}
