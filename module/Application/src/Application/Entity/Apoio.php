<?php

namespace Application\Entity;

use Extended\Table\CapturaObject;

class Apoio extends CapturaObject
{

    /**
     * @name ='id_apoio'
     * @type='integer'
     */
    protected $id_apoio;

    /**
     * @name ='apoio'
     * @type='string'
     */
    protected $apoio;
    
    /**
     * @name ='empresa'
     * @type='string'
     */
    protected $empresa;
    
    /**
     * @name ='email'
     * @type='string'
     */
    protected $email;

    /**
     *
     * @name ='data'
     * @type='string'
     */
    protected $data;
    
    /**
     * Método que retorna o id do apoio.
     */
    public function getId()
    {
    	return $this->id_apoio;
    }

    /**
     * Método que retorna uma data em formato pt-br para a data de inicio de uma batalha.
     */
    public function getDataBr()
    {
        return date('d/m/Y', strtotime($this->data));
    }
    
}