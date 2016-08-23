<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class User extends CapturaObject
{
    /**
     * @name='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;

    /**
     * @name='nome'
     * @type='string'
     */
    protected $nome;
    
    /**
     * @name='login'
     * @type='string'
     */
    protected $login;
    
    /**
     * @name='senha'
     * @type='string'
     */
    protected $senha;
    
    /**
     * @name='cpf'
     * @type='string'
     */
    protected $cpf;
    
    /**
     * @name='email'
     * @type='string'
     */
    protected $email;
    
    /**
     * @name='telefone'
     * @type='string'
     */
    protected $telefone;

    /**
     * @name='ativo'
     * @type='boolean'
     */
    protected $ativo;
    
    /**
     * @name='tipo'
     * @type='string'
     */
    protected $tipo;
    
    /**
     * @name='id_facebook'
     * @type='Integer'
     */
    protected $id_facebook;
    
    /**
     * @name='pontos'
     * @type='Integer'
     */
    protected $pontos;
    
    /**
     * @exclude
     */
    protected $batalhando;
    
    /**
	 * @return the $id_usuario
	 */
	public function getId() {
		return $this->id_usuario;
	}
    /**
	 * @return the $cpf
	 */
	public function getCpf() {
		return $this->cpf;
	}
	
	/**
	 * Método que liga o modo batalha.
	 */
	public function ligarModoBatalha()
	{
	    $this->batalhando = true;
	}
	
	/**
	 * Método que desliga o modo batalha.
	 */
	public function desligarModoBatalha()
	{
	    $this->batalhando = false;
	}
	
	/**
	 * Método que informa se o usuário está batalhando.
	 */
	public function getBatalhando()
	{
	    return $this->batalhando ? true : false;
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
    
}