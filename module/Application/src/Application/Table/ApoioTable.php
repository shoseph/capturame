<?php
namespace Application\Table;
use Application\Entity\Apoio;

use Zend\Db\Sql\Expression;

use Capturada\Entity\CapturadaTag;

use Capturada\Entity\Tag;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada_tag. 
 */
class ApoioTable extends CapturaTable{
    
    protected $table ='cp_apoio';
    protected $key = 'id_apoio';
    
        
    /**
     * Método que salva uma tag.
     * @param Tag $capturada Objeto de entidade de uma tag.
     */
    public function save(Apoio $apoio)
    {
    	if ($apoio->getId() == 0) {
    		$this->insert($apoio->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($apoio->getId()) {
    		$this->update($apoio->toArray(),array('id_apoio' => $apoio->getId(),));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
}
