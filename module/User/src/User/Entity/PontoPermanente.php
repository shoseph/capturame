<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class PontoPermanente extends CapturaObject
{
    /**
     * @name='id_ponto_permanente'
     * @type='integer'
     */
    protected $id_ponto_permanente;
   
    /**
     * @name='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;
    
    /**
     * @name='id_acao'
     * @type='integer'
     */
    protected $id_acao;
    
    /**
     * @name='id_reputacao'
     * @type='integer'
     */
    protected $id_reputacao;
    
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
     * @name='soma'
     * @type='integer'
     */
    protected $soma;
    
    public function getId()
    {
    	return $this->id_ponto_permanente;
    }
	
}