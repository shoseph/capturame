<?php 
namespace User\Table;
use User\Entity\TipoConteudo;

use User\Entity\Conteudo;

use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;
use User\Entity\User as User;

class ConteudoTable extends CapturaTable
{
    protected $table ='cp_conteudo';
    protected $key = 'id_conteudo';

    /**
     * Método que insere/atualiza um novo conteúdo
     * @param User $user Usuário.
     * @throws \Exception
     */
    public function save(Conteudo $conteudo)
    {
    	if ($conteudo->getId() == 0) {
    		$this->insert($conteudo->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getConteudo($conteudo->getId())) {
    		$this->update(
    				$conteudo->toArray(),
    				array(
    						'id_conteudo' => $conteudo->getId(),
    				)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }
    
    /**
     * Método que retorna um conteúdo.
     * @param String $idConteudo identificador de um conteúdo
     */
    public function getConteudo($idConteudo)
    {
    	$select = $this->sql->select()
    	->where(array('id_conteudo' => $idConteudo))
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna o ultimo conteúdo.
     * @param TipoConteudo $tipoConteudo objeto do tipo TipoConteudo
     */
    public function getUltimoConteudo(TipoConteudo $tipoConteudo)
    {
    	$select = $this->sql->select()
    	->where(array('id_tipo' => $tipoConteudo->id_tipo_conteudo))
    	->order('id_conteudo DESC')
    	->limit(1)
    	;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna conteudos de um determinado tipo;
     * @param TipoConteudo $tipoCapturada tipo do conteudo em questão;
     */
    public function getConteudos($tipoCapturada,  $limit = null, $offset = null)
    {
    	$select = $this->sql->select()
    	    ->where(array('id_tipo' => $tipoCapturada->id_tipo_conteudo))
    	    ->order('id_conteudo DESC')
    	;
    	!$limit ?: $select->limit($limit);
    	!$offset ?: $select->offset($offset);
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna conteudos de um determinado tipo;
     * @param TipoConteudo $tipoCapturada tipo do conteudo em questão;
     */
    public function getTimeline($limit = null, $offset = null)
    {
    	$select = $this->sql->select()
    	    ->order('data DESC')
    	;
    	!$limit ?: $select->limit($limit);
    	!$offset ?: $select->offset($offset);
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que remove um conteúdo.
     * @param User $user
     */
    public function deleteConteudo(Conteudo $conteudo)
    {
    	$this->delete(array(
    	    'id_conteudo' => $conteudo->id_conteudo
    	));
    }
}