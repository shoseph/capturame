<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class PontoMensal extends CapturaObject
{
    /**
     * @name='id_ponto_mensal'
     * @type='integer'
     */
    protected $id_ponto_mensal;
   
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
     * @name='id_classificacao'
     * @type='integer'
     */
    protected $id_classificacao;
    
    /**
     * @name='soma'
     * @type='integer'
     */
    protected $soma;
    
    public function getId()
    {
    	return $this->id_ponto_mensal;
    }
	
}