<?php 
namespace User\Table;
use Extended\Table\CapturaTable;

class ClassificacaoTable extends CapturaTable
{
    protected $table ='cp_classificacao';
    protected $key = 'id_classificacao';
    
    
    /**
     * Método que retona qual é o proxima classificação
     * @param Integer $somatorioPontos quantidade de pontos atuais
     */
    public function getProximaClassificacao($somatorioPontos)
    {
    	$select = $this->sql->select()
    	->where(array("{$this->table}.pontos >= ? or ({$this->table}.pontos >= 4000)" => $somatorioPontos))
    	->order(array('pontos ASC'))
    	;

    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a primeira classificação caso o usuário
     * ainda não possua uma classificação este mês.
     */
    public function getPrimeiraClassificacao()
    {
    	$select = $this->sql->select()->where(array('pontos = ?' => 500));
    	return $this->fetchAll($select);
    }

}