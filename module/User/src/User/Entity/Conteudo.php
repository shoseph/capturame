<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class Conteudo extends CapturaObject
{
    /**
     * @name='id_conteudo'
     * @type='integer'
     */
    protected $id_conteudo;

    /**
     * @name='id_tipo'
     * @type='integer'
     */
    protected $id_tipo;
    
    /**
     * @name='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;

    /**
     * @name='titulo'
     * @type='string'
     */
    protected $titulo;

    /**
     * @name='conteudo'
     * @type='string'
     */
    protected $conteudo;
        
    /**
     * @name='curtir'
     * @type='string'
     */
    protected $curtir;
    
    /**
     * @name='data'
     * @type='string'
     */
    protected $data;
    
    /**
     * @name='link'
     * @type='string'
     */
    protected $link;
        
    /**
	 * @return the $id_usuario
	 */
	public function getId() {
		return $this->id_conteudo;
	}
	
	public function getResumo(){
	    return \Captura::resumo(strip_tags($this->conteudo), 200);
	}
		
	/**
	 * Método que retorna uma data em formato pt-br
	 */
	public function getData()
	{
		return date('d/m/Y', strtotime($this->data));
	}
	
	/**
	 * Método que retorna uma hora
	 */
	public function getHora()
	{
		return date('h:i', strtotime($this->data));
	}
	
	
}