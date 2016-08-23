<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class BatalhaCapturada extends CapturaObject
{

    /**
     *
     * @name ='id_batalha_capturada'
     * @type='integer'
     */
    protected $id_batalha_capturada;
    
    /**
     *
     * @name ='id_batalha'
     * @type='integer'
     */
    protected $id_batalha;

    /**
     *
     * @name ='id_usuario'
     * @type='integer'
     */
    protected $id_usuario;

    /**
     *
     * @name ='id_capturada'
     * @type='integer'
     */
    protected $id_capturada;

    /**
     *
     * @name ='id_capturada_anterior'
     * @type='integer'
     */
    protected $id_capturada_anterior;

    /**
     * Método que retorna o id da batalha.
     */
    public function getId()
    {
        return $this->id_batalha_capturada;
    }
    
    /**
     * Método que seta uma batalha em vez do id.
     * @param Batalha $batalha objeto de uma batalha
     */
    public function setBatalha(Batalha $batalha){
        $this->batalha = $batalha;
    }
    
    /**
     * Método que seta uma capturada em vez do id.
     * @param Capturada $capturada objeto de uma capturada
     */
    public function setCapturada(Capturada $capturada){
        $this->capturada = $capturada;
    }
    
    /**
     * Método que retorna uma batalha.
     * @return Batalha objeto do tipo batalha.
     */
    public function getBatalha()
    {
        return $this->batalha;    
    }
    
    /**
     * Método que retorna uma capturada
     * @return Capturada objeto do tipo capturada. 
     */
    public function getCapturada()
    {
        return $this->capturada;    
    }
    
    /**
     * Método que cadastra a quantidade de pegou
     */
    public function setPegou($pegou)
    {
        $this->pegou = $pegou;
    }

    /**
     * Método que retorna a quantidade de pegou
     */
    public function getPegou()
    {
        return $this->pegou;
    }
    /**
     * Método que cadastra a quantidade de não pegou
     */
    public function setNaoPegou($naoPegou)
    {
        $this->naoPegou = $naoPegou;
    }

    /**
     * Método que retorna a quantidade de não pegou
     */
    public function getNaoPegou()
    {
        return $this->naoPegou;
    }
}