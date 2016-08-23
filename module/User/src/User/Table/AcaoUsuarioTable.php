<?php 
namespace User\Table;
use Zend\Db\Sql\Expression;

use User\Entity\AcaoUsuario;

use Extended\Table\CapturaTable;

class AcaoUsuarioTable extends CapturaTable
{
    protected $table ='cp_acao_usuario';
    protected $key = 'id_acao_usuario';
    
    /**
     * Método que insere/atualiza uma nova ação do usuário
     * @param AcaoUsuario $acaoUsuario Objeto de ação do usuário.
     */
    public function save(AcaoUsuario $acaoUsuario)
    {
    	if ($acaoUsuario->getId() == 0) {
    		$this->insert($acaoUsuario->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getUsuario($acaoUsuario->getId())) {
    		$this->update(
    		    $acaoUsuario->toArray(),
    			array('id_usuario' => $acaoUsuario->getId(),)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }
    
    /**
     * Método que retorna a quantidade metal da ação executada
     * @param Array $acoes identificador da ação
     * @return number total de ações
     */
    public function getQuantidadeMensalAcao(array $acoes)
    {
        
    	$select = $this->sql->select()
    	               ->columns(array(new Expression('count(*) as total')))
    	               ->where(array('id_acao in (?)' => implode($acoes,',')))
    	               ->where("mes = DATE_FORMAT(CURRENT_TIMESTAMP,'%m')")
    	               ->where("ano = DATE_FORMAT(CURRENT_TIMESTAMP,'%Y')")
    	;
    	
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    
    
    public function getNovidades($limit = null, $offset = null)
    {
//     	$select = $this->sql->select()
//     	->join(
//     			array('cp' => 'cp_capturada')
//     			, "cp.id_capturada = {$this->table}.id_capturada"
//     			, array('*')
//     			)->order(new Expression('cp.dtImagem DESC'))
//     			->group("{$this->table}.id_capturada")
//     			;
    			 
//     			!$limit ?: $select->limit($limit);
//     			!$offset ?: $select->offset($offset);
//     			return $this->fetchAll($select);
    }

}