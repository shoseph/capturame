<?php 
namespace User\Table;
use User\Entity\PontoMensal;

use Extended\Table\CapturaTable;

class PontoMensalTable extends CapturaTable
{
    protected $table ='cp_ponto_mensal';
    protected $key = 'id_ponto_mensal';
    
    
    
    /**
     * Método que retona a soma de pontos de um usuário
     * @param Integer $idUsuario identificador de um usuário
     */
    public function getSomaPontosUsuario($idUsuario)
    {
    	$select = $this->sql->select()->columns(array('soma'))
    	->where(array("{$this->table}.id_usuario" => $idUsuario))
    	->order(array('soma DESC'))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna um objeto do tipo PontosMensal
     * de um usuário.
     * @param Integer $idUsuario identificador de um usuário.
     */
    public function getPontosUsuario($idUsuario)
    {
    	$select = $this->sql->select()
    	->where(array("{$this->table}.id_usuario" => $idUsuario))
    	->order(array('soma DESC'))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que insere/atualiza um ponto mensal
     * @param PontoPermanente $pontoMensal Objeto de ação do usuário.
     */
    public function save(PontoMensal $pontoMensal)
    {
    	if ($pontoMensal->getId() == 0) {
    		$this->insert($pontoMensal->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getUsuario($pontoMensal->getId())) {
    		$this->update(
    				$pontoMensal->toArray(),
    				array('id_ponto_mensal' => $pontoMensal->getId(),)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }

}