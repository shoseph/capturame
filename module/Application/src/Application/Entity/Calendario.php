<?php

namespace Application\Entity;

use Extended\Table\CapturaObject;

class Calendario extends CapturaObject
{

    /**
     * @name ='titulo'
     * @type='string'
     */
    protected $titulo;

    /**
     * @name ='onde'
     * @type='string'
     */
    protected $onde;

    /**
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
     * @name ='hrInicio'
     * @type='string'
     */
    protected $hrInicio;

    /**
     *
     * @name ='dtFim'
     * @type='string'
     */
    protected $dtFim;
    
    /**
     *
     * @name ='hrFim'
     * @type='string'
     */
    protected $hrFim;

    /**
     * @name ='email'
     * @type='string'
     */
    protected $email;
    
    /**
     * @exclude
     */
    protected $event;
    
    /**
     * @exclude
     */
    protected $offset = '-03';

    /**
     * @exclude
     */
    protected $service;
    
    /**
     * Método que formata e retorna quando vai ser o evento.
     * @return unknown
     */
    public function getWhen($service)
    {
//         $data_br = preg_replace('/^(\d{4})-(\d{2})-(\d{2})$/', '$3/$2/$1', $data_en);
        $dataInicio = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $this->dtInicio);
        $dataFim = (!$this->dtFim) ? $dataInicio : preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $this->dtFim);
        $this->hrFim ?: $this->hrFim = $this->hrInicio;
        $when = $service->newWhen();
        $when->startTime = "{$dataInicio}T{$this->hrInicio}:00.000{$this->offset}:00";
        $when->endTime = "{$dataFim}T{$this->hrFim}:00.000{$this->offset}:00";
        return $when;
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