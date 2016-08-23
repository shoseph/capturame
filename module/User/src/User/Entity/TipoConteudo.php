<?php

namespace User\Entity;

use Extended\Table\CapturaObject;

class TipoConteudo extends CapturaObject
{
    /**
     * @name='id_tipo_conteudo'
     * @type='integer'
     */
    protected $id_tipo_conteudo;

    /**
     * @name='tipo'
     * @type='string'
     */
    protected $tipo;
        
    /**
	 * @return the $id_usuario
	 */
	public function getId() {
		return $this->id_tipo_conteudo;
	}
	
}