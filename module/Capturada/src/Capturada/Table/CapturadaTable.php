<?php
namespace Capturada\Table;
use Capturada\Entity\Capturada;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
class CapturadaTable extends CapturaTable{
    
    protected $table ='cp_capturada';
    protected $key = 'id_capturada';
    
    /**
     * Método que retorna uma capturada.
     * @param String $idCapturada identificador da tabela cp_capturada
     * @return \Extended\Table\Ambigous
     */
    public function getCapturada($idCapturada)
    {
        $select = $this->sql->select()
        		->where(array('id_capturada' => $idCapturada))
        		->order('nome asc');
        
        return $this->fetchAll($select);
    }
    /**
     * Método que busca uma capturada pelo hash formado na foto.
     * @param Capturada $capturada Objeto do tipo capturada.
     */
    public function getCapturadaPorHash(Capturada $capturada)
    {
        $select = $this->sql->select()
            ->where(array('hash' => $capturada->getHash()));
        return $this->fetchAll($select);
    }
    
    /**
     * Método que salva uma capturada.
     * @param Capturada $capturada Objeto de entidade de uma capturada.
     * @throws \Exception
     */
    public function save(Capturada $capturada)
    {
        
    	if ($capturada->getId() == 0) {
    		$this->insert($capturada->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($capturada->getId()) {
    		$this->update($capturada->toArray(),array(
    		    'id_capturada' => $capturada->getId(),
    		));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
}
