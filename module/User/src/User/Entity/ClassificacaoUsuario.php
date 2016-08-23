<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class ClassificacaoUsuario extends CapturaObject
{
   
    /**
     * @name='id_classificacao_usuario'
     * @type='integer'
     */
    protected $id_classificacao_usuario;
    
    /**
     * @name='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;

    /**
     * @name='id_classificacao'
     * @type='integer'
     */
    protected $id_classificacao;
    
    /**
     * @name='mes'
     * @type='integer'
     */
    protected $mes;
    /**
     * @name='ano'
     * @type='integer'
     */
    protected $ano;
    
    /**
     * @exclude
     */
	protected $classificacao;
	
	/**
	 * @return the $id_usuario
	 */
	public function getId() {
		return $this->id_classificacao_usuario;
	}
	
}