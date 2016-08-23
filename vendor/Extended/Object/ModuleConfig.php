<?php

namespace Extended\Object;

use \Extended\Controller\CapturaController;

class ModuleConfig 
{
    protected static $_instance = null;
    
        
    /**
     * Método que preenche os itens referentes a classe.
     */
    private function setItens()
    {
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
            $c = __CLASS__;
    		self::$_instance = new $c();
    	}
    	return self::$_instance;
    }
    
    /**
     * Método que cria uma rota literal
     * @param String $name Nome da chamada para a rota
     * @param String $route caminho da rota
     * @param String $module nome do módulo
     * @param String $controller nome do controller
     * @param String $action nome da action
     * @param Int $priority prioridade da rota
     */
    public static function getLiteralRoute($name, $route, $module, $controller, $action, $priority = 2)
    {
        $type = 'Literal';
        return self::getRoute($name, $route, $module, $controller, $action, $type, $priority);
    }
    
    /**
     * Método que cria uma rota segmentada
     * @param String $name Nome da chamada para a rota
     * @param String $route caminho da rota
     * @param String $module nome do módulo
     * @param String $controller nome do controller
     * @param String $action nome da action
     * @param Int $priority prioridade da rota
     */
    public static function getSegmentRoute($name, $route, $module, $controller, $action, $constraint = null, $priority = 3)
    {
        $type = 'Segment';
        return self::getRoute($name, $route, $module, $controller, $action, $type, $priority, $constraint);
    }
    /**
     * Método que cria uma rota 
     * @param String $name Nome da chamada para a rota
     * @param String $route caminho da rota
     * @param String $module nome do módulo
     * @param String $controller nome do controller
     * @param String $action nome da action
     * @param String $type Tipo da rota
     * @param Int $priority prioridade da rota
     * @param String $constraints Variável que contem as contraints
     * @return array
     */
    public static function getRoute($name, $route, $module, $controller, $action, $type, $priority = null, $constraints = null)
    {
        $options = array(
            'route' => $route,
			'defaults' => array(
					'__NAMESPACE__' => ucfirst($module) . '\Controller',
					'module'        => $module,
					'controller'    => $controller,
					'action'        => $action,
			),
        );
        !$constraints ?: $options['constraints'] = $constraints;
        $config = array('type' => $type, 'options' => $options);
        !$priority ?: $config['priority'] = $priority;
        return array($name => $config);
    }
    
    
    public static function getDefaultRoute(){
        return array(
            'application' => array(
            		'may_terminate' => true,
            		'type'    => 'segment',
                    'priority' => 1,
            		'options' => array(
            				'route'    => '[/:module][/:controller][/:action][/][foto[/:foto]]',
            				'constraints' => array(
            						'module' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'foto'     => '[a-z0-9_-]+',
            				),
            				'defaults' => array(
            				        '__NAMESPACE__' => 'Application\Controller',
            						'module'     => 'application',
            						'controller' => 'index',
            						'action'     => 'index',
            				),
            		),
            ),
        );   
    }
    
	/**
	 * Implementando o método get para acesso dos itens
	 * de forma rapida.
	 * @param String $name nome da variável.
	 */
    public function __get($name)
    {
        $nome = $name[0] == '_' ? $name : "_{$name}";
        return $this->$nome;
    }

}