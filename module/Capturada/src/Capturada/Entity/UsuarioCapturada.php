<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class UsuarioCapturada extends CapturaObject
{
    /**
     *
     * @name ='id_usuario'
     *       @type='integer'
     */
    protected $id_usuario;
    
    /**
     *
     * @name ='id_capturada_anterior'
     *       @type='integer'
     */
    protected $id_capturada_anterior;
    
    /**
     *
     * @name ='id_capturada'
     *       @type='integer'
     */
    protected $id_capturada;

    /**
     *
     * @name ='gostou'
     * @type='string'
     */
    protected $gostou;

    /**
     *
     * @name ='ficou'
     * @type='string'
     */
    protected $ficou;
    
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