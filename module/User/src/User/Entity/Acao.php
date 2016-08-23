<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class Acao extends CapturaObject
{
   
    /**
     * @name='id_acao'
     * @type='integer'
     */
    protected $id_acao;
    
    /**
     * @name='nome'
     * @type='string'
     */
    protected $nome;
    
    /**
     * @name='ponto_permanente'
     * @type='integer'
     */
    protected $ponto_permanente;
    
    /**
     * @name='ponto_mensal'
     * @type='integer'
     */
    protected $ponto_mensal;
    
    public function getId()
    {
    	return $this->id_acao;
    }
	
}