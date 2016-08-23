<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class BatalhaPonto extends CapturaObject
{

    /**
     *
     * @name ='id_batalha_ponto'
     * @type='integer'
     */
    protected $id_batalha_ponto;

    /**
     *
     * @name ='id_usuario_acao'
     * @type='integer'
     */
    protected $id_usuario_acao;

    /**
     *
     * @name ='id_batalha_capturada'
     * @type='integer'
     */
    protected $id_batalha_capturada;

    /**
     *
     * @name ='pegou'
     * @type='integer'
     */
    protected $pegou;

    /**
     *
     * @name ='naoPegou'
     * @type='integer'
     */
    protected $naoPegou;

    /**
     *
     * @name ='baixou'
     * @type='integer'
     */
    protected $baixou;
    
    /**
     *
     * @name ='dtCadastro'
     *       @type='string'
     */
    protected $dtCadastro;
        
    /**
     * Método que retorna o id da batalha.
     */
    public function getId()
    {
        return $this->id_batalha_ponto;
    }
    
}