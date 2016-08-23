<?php

namespace User\Entity;

class Window 
{
    
    protected static $_instance = null;
    protected static $_session = null;
    const WINDOW_CONF = 'config-window';
   
    private function __construct(){}
    
    /**
     * Chamada do método que garante a instanciação única do Window.
     * @return Window Retorna a classe Window.
     */
    
    public static function getInstance()
    {
    	if (!isset(self::$_instance)) {
    		$c = __CLASS__;
    		self::$_session = new \Zend\Session\Container('window');
    		self::$_instance = new $c();
    	}
    	return self::$_instance;
    }
    /**
     * Método que retorna o usuário logado.
     */
    public static function getWindow()
    {
        return self::$_session->offsetGet(self::WINDOW_CONF);
    }
    
    /**
     * Método que verifica se o usuário existe na sessão.
     */
    public static function exists()
    {
        return self::$_session->offsetExists(self::WINDOW_CONF);
    }
    
    /**
     * Método que faz o set no usuário.
     * @param Window $window Objeto do tipo window.
     */
    public static function setWindow($window)
    {
        self::$_session->offsetSet(self::WINDOW_CONF, $window);
    }
    
    /**
     * Método que apaga o a window da sessão, fazendo assim não ter
     * usuário logo deslogando no sistema.
     */
    public static function destroy()
    {
        
        self::$_session->offsetUnset(self::WINDOW_CONF);
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