<?php 
namespace User\Table;
use Extended\Table\CapturaTable;

class ReputacaoTable extends CapturaTable
{
    protected $table ='cp_reputacao';
    protected $key = 'id_reputacao';
    
    
    /**
     * Método que retona qual é o próxima reputação.
     * @param Integer $somatorioPontos quantidade de pontos atuais
     */
    public function getProximaReputacao($somatorioPontos)
    {
    	$select = $this->sql->select()
    	->where(array("({$this->table}.pontos + {$this->table}.intervalo) > ? " => $somatorioPontos))
    	->order(array('pontos ASC'))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a primeira reputacao caso o usuário
     * ainda não possua uma reputação.
     */
    public function getPrimeiraReputacao()
    {
    	$select = $this->sql->select()->where(array('pontos = ?' => 0));
    	return $this->fetchAll($select);
    }
    

}