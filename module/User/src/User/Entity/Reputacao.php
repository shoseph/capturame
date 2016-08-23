<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class Reputacao extends CapturaObject
{
   
    /**
     * @name='id_reputacao'
     * @type='integer'
     */
    protected $id_reputacao;
    
    /**
     * @name='nome_reputacao'
     * @type='string'
     */
    protected $nome_reputacao;
    
    /**
     * @name='pontos'
     * @type='integer'
     */
    protected $pontos;
    
    /**
     * @name='intervalo'
     * @type='integer'
     */
    protected $intervalo;
    
    public function getId()
    {
    	return $this->id_reputacao;
    }
    
    public function getNumeroMaximoDePontos()
    {
        return $this->intervalo + $this->pontos;    
    }
}