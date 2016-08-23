<?php 
namespace User\Table;
use User\Entity\ReputacaoUsuario;

use Extended\Table\CapturaTable;

class ReputacaoUsuarioTable extends CapturaTable
{
    protected $table ='cp_reputacao_usuario';
    protected $key = 'id_reputacao_usuario';
    
    /**
     * Método que retorna um objeto reputacao usuário.
     * @param Integer $idUsuario identificador do usuário
     * @return \Extended\Table\Ambigous
     */
    public function getUltimaReputacao($idUsuario)
    {
    	$select = $this->sql->select()
    	->join(array('rep' => 'cp_reputacao')
    			, "rep.id_reputacao = {$this->table}.id_reputacao"
    			, array('*')
    			)
    			->where(array("{$this->table}.id_usuario" => $idUsuario))
    			->order(array('mes DESC','ano DESC'))
    			;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna uma reputação usuário
     * @param Integer $idReputacaoUsuario identificador de uma reputação usuário
     */
    public function getReputacaoUsuario($idReputacaoUsuario)
    {
    	$select = $this->sql->select()->where(array($this->key => $idReputacaoUsuario));
    	return $this->fetchAll($select);
    }

    /**
     * Método que salva ou atualiza uma Reputação Usuário
     * @param ReputacaoUsuario $reputacaoUsuario Objeto Classificação Usuário.
     * @return number
     */
    public function save(ReputacaoUsuario $reputacaoUsuario)
    {
    	if ($reputacaoUsuario->getId() == 0) {
    		$this->insert($reputacaoUsuario->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getReputacaoUsuario($reputacaoUsuario->getId())) {
    		$this->update($reputacaoUsuario->toArray(),
    				array($this->key => $reputacaoUsuario->getId(),)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }
    
}