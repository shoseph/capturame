<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class Notificacao extends CapturaObject
{
    /**
     *
     * @name ='id_notificacao'
     *       @type='integer'
     */
    protected $id_notificacao;
    /**
     *
     * @name ='id_usuario'
     *       @type='integer'
     */
    protected $id_usuario;
    
    /**
     *
     * @name ='id_tipo_notificacao'
     *       @type='integer'
     */
    protected $id_tipo_notificacao;
    
    /**
     *
     * @name ='quantidade'
     *       @type='integer'
     */
    protected $quantidade;

    /**
     * @name='ano'
     * @type='integer'
     */
    protected $ano;
    
    /**
     * @name='mes'
     * @type='integer'
     */
    protected $mes;
    
    public function getId()
    {
    	return $this->id_notificacao;
    }
}