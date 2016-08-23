<?php 
namespace User\Table;
use User\Entity\ClassificacaoUsuario;

use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;

class ClassificacaoUsuarioTable extends CapturaTable
{
    protected $table ='cp_classificacao_usuario';
    protected $key = 'id_classificacao_usuario';

    
    /**
     * Método que retorna um objeto classificação usuário.
     * @param Integer $idUsuario identificador do usuário
     * @return \Extended\Table\Ambigous
     */
    public function getUltimaClassificacao($idUsuario)
    {
    	$select = $this->sql->select()
    	    ->join(array('cl' => 'cp_classificacao')
    		    , "cl.id_classificacao = {$this->table}.id_classificacao"
    		    , array('*')
    		)
            ->where(array("{$this->table}.id_usuario" => $idUsuario))
            ->order(array('mes DESC','ano DESC'))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de classificações ouro para o mes e ano desejado.
     * @param Integer $mes mês requisitado.
     * @param Integer $ano ano requisitado.
     */
    public function getCountClassificacaoOuro($mes, $ano)
    {
        
    	$select = $this->sql->select()
    	    ->columns(array(new Expression('count(*) as total')))
    	    ->join(array('cl' => 'cp_classificacao')
    		    , "cl.id_classificacao = {$this->table}.id_classificacao"
    		    , array()
    		)
            ->where(array("cl.nome like ? " => '%ouro%'))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna uma classificação usuário
     * @param Integer $idClassificacaoUsuario identificador de uma  classificação usuário
     */
    public function getClassificacaoUsuario($idClassificacaoUsuario)
    {
    	$select = $this->sql->select()->where(array($this->key => $idClassificacaoUsuario));
    	return $this->fetchAll($select);
    }
        
    /**
     * Método que salva ou atualiza uma Classificacao Usuário
     * @param ClassificacaoUsuario $classificacaoUsuario Objeto Classificação Usuário.
     * @return number
     */
    public function save(ClassificacaoUsuario $classificacaoUsuario)
    {
    	if ($classificacaoUsuario->getId() == 0) {
    		$this->insert($classificacaoUsuario->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getClassificacaoUsuario($classificacaoUsuario->getId())) {
    		$this->update($classificacaoUsuario->toArray(),
    				array($this->key => $classificacaoUsuario->getId(),)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }
    
    
}