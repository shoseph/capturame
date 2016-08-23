<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class Classificacao extends CapturaObject
{
   
    /**
     * @name='id_classificacao'
     * @type='integer'
     */
    protected $id_classificacao;
    
    /**
     * @name='nome'
     * @type='string'
     */
    protected $nome;
    
    /**
     * @name='pontos'
     * @type='integer'
     */
    protected $pontos;
    
    /**
     * @name='multiplicador'
     * @type='integer'
     */
    protected $multiplicador;
    
    public function getId()
    {
    	return $this->id_classificacao;
    }
	
}