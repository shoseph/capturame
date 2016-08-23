<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class Tag extends CapturaObject
{

    /**
     *
     * @name ='id_tag'
     *       @type='integer'
     */
    protected $id_tag;

    /**
     *
     * @name ='nome'
     *       @type='string'
     */
    protected $nome;
    
    /**
     *
     * @name ='dt_tag'
     *       @type='string'
     */
    protected $dt_tag;
    
    /**
     *
     * @name ='evento'
     *       @type='integer'
     */
    protected $evento;
    
    /**
     *
     * @name ='data'
     *       @type='string'
     */
    protected $data;
    
    /**
     *
     * @name ='hora'
     *       @type='string'
     */
    protected $hora;

    /**
     *
     * @return the $id_tag
     */
    public function getId ()
    {
        return $this->id_tag;
    }
    
    /**
     * Método que retorna uma data em formato pt-br
     */
    public function getData()
    {
    	return date('d/m/Y', strtotime($this->dt_tag));
    }
    
    /**
     * Método que retorna uma hora
     */
    public function getHora()
    {
    	return date('h:i', strtotime($this->dt_tag));
    }

}