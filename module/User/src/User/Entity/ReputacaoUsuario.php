<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class ReputacaoUsuario extends CapturaObject
{
   
    /**
     * @name='id_reputacao_usuario'
     * @type='integer'
     */
    protected $id_reputacao_usuario;
    
    /**
     * @name='id_reputacao'
     * @type='integer'
     */
    protected $id_reputacao;
    
    /**
     * @name='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;
    
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
    
    public function getId()
    {
    	return $this->id_reputacao_usuario;
    }
	
}