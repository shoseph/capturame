<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class AcaoUsuario extends CapturaObject
{
   
    /**
     * @name='id_acao_usuario'
     * @type='integer'
     */
    protected $id_acao_usuario;
    
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
        return $this->id_acao_usuario;
    }
}