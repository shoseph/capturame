<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class Batalha extends CapturaObject
{

    /**
     *
     * @name ='id_batalha'
     * @type='integer'
     */
    protected $id_batalha;

    /**
     *
     * @name ='titulo'
     * @type='string'
     */
    protected $titulo;

    /**
     *
     * @name ='descricao'
     * @type='string'
     */
    protected $descricao;

    /**
     *
     * @name ='dtInicio'
     * @type='string'
     */
    protected $dtInicio;

    /**
     *
     * @name ='dtFim'
     * @type='string'
     */
    protected $dtFim;
    
    /**
     *
     * @name ='aberta'
     * @type='boolean'
     */
    protected $aberta;
    
    /**
     *
     * @name ='quantidade'
     * @type='integer'
     */
    protected $quantidade;

    
    /**
     * Método que retorna o id da batalha.
     */
    public function getId()
    {
        return $this->id_batalha;
    }
    
    /**
     * Método que retorna uma data em formato pt-br para a data de inicio de uma batalha.
     */
    public function getDataInicioBr()
    {
        return date('d/m/Y', strtotime($this->dtInicio));
    }
    
    /**
     * Método que retorna uma data em formato pt-br para a data de finalização de uma batalha.
     */
    public function getDataFimBr()
    {
        return date('d/m/Y', strtotime($this->dtFim));
    }
}