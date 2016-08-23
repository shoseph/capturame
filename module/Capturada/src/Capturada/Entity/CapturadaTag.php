<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class CapturadaTag extends CapturaObject
{

    /**
     *
     * @name ='id_tag'
     *       @type='integer'
     */
    protected $id_capturada_tag;
    /**
     *
     * @name ='id_tag'
     *       @type='integer'
     */
    protected $id_tag;
    /**
     *
     * @name ='id_capturada'
     *       @type='integer'
     */
    protected $id_capturada;

    /**
     *
     * @name ='nome'
     *       @type='string'
     */
    protected $nome;

    /**
     *
     * @return the $id_capturada_tag
     */
    public function getId ()
    {
        return $this->id_capturada_tag;
    }
    
    /**
     * Método que seta um objeto tag.
     * @param Tag $Tag Objeto de tipo tag.
     */
    public function setTag(Tag $tag){
        $this->tag = $tag;
    }
    
    /**
     * Método que retorna o objeto tag.
     */
    public function getTag(){
        return $this->tag;
    }
    
    /**
     * Método que seta um objeto capturada.
     * @param Capturada $capturada Objeto de tipo capturada.
     */
    public function setCapturada(Capturada $capturada)
    {
    	$this->capturada = $capturada;
    }
    
    /**
     * Método que retorna o objeto capturada.
     */
    public function getCapturada()
    {
    	return $this->capturada;
    }

}