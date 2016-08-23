<?php

namespace User\Auth;

class CapturaAuth 
{
    
    protected static $_instance = null;
    protected static $_session = null;
    const USUARIO_LOGADO = 'usuario';
   
    private function __construct(){}
    
    /**
     * Chamada do método que garante a instanciação única do Auth.
     * @return CapturaAuth Retorna a classe CapturaAuth.
     */
    
    public static function getInstance()
    {
    	if (!isset(self::$_instance)) {
    		$c = __CLASS__;
    		self::$_session = new \Zend\Session\Container('user');
    		self::$_instance = new $c();
    	}
    	return self::$_instance;
    }
    /**
     * Método que retorna o usuário logado.
     */
    public static function getUser()
    {
        return self::$_session->offsetGet(self::USUARIO_LOGADO);
    }
    
    /**
     * Método que verifica se o usuário existe na sessão.
     */
    public static function exists()
    {
        return self::$_session->offsetExists(self::USUARIO_LOGADO);
    }
    
    /**
     * Método que faz o set no usuário.
     * @param User $user Objeto do tipo usuário.
     */
    public static function setUser($user)
    {
        self::$_session->offsetSet(self::USUARIO_LOGADO, $user);
    }
    
    /**
     * Método que apaga o usuário da sessão, fazendo assim não ter
     * usuário logo deslogando no sistema.
     */
    public static function logout()
    {
        
        self::$_session->offsetUnset(self::USUARIO_LOGADO);
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