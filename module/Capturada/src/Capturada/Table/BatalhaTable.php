<?php
namespace Capturada\Table;
use Zend\Db\Sql\Expression;

use Zend\Db\ResultSet\ResultSet;

use Capturada\Entity\Batalha;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
class BatalhaTable extends CapturaTable{
    
    protected $table ='cp_batalha';
    protected $key = 'id_batalha';
    
    /**
     * Método que salva uma capturada.
     * @param Capturada $capturada Objeto de entidade de uma capturada.
     * @throws \Exception
     */
    public function save(Batalha $batalha)
    {
    	if ($batalha->getId() == 0) {
    		$this->insert($batalha->toArray());
    	} elseif ($batalha->getId()) {
    		$this->update($batalha->toArray(),array(
    		    $this->key => $batalha->getId(),
    		));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
    /**
     * Método que retorna qual é a batalha atual
     */
    public function getBatalhasAtuais()
    {
        $select = $this->sql->select()->where('aberta = 1');
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de batalhas abertas
     * @return number total de batalhas
     */
    public function getQuantidadeBatalhasAbertas()
    {
    
    	$select = $this->sql->select()
    	               ->columns(array(new Expression('count(*) as total')))
    	               ->where('aberta = 1')
    	;
    	 
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna qual é a batalha atual
     */
    public function getBatalha($batalha)
    {
        $select = $this->sql->select()->where(array('id_batalha' => $batalha));
        $retorno = $this->fetchAll($select);
        return $retorno->count() ? $retorno->current() : null;
    }
    
}
