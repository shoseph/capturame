<?php
namespace Capturada\Table;
use Zend\Db\Sql\Expression;

use Capturada\Entity\CapturadaTag;

use Capturada\Entity\Tag;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada_tag. 
 */
class CapturadaTagTable extends CapturaTable{
    
    protected $table ='cp_capturada_tag';
    protected $key = 'id_tag';
    
    /**
     * Método que retorna uma tag.
     * @param Tag Objeto do tipo tag
     */
    public function getCapturadaTag(CapturadaTag $capturadaTag)
    {
        $select = $this->sql->select()
        		->where($capturadaTag->toArray())
        ;
        
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de tags.
     * @param Integer $idUsuario Identificador de um usuário.
     */
    public function getQuantidadeTagsCapturada(Tag $tag)
    {
    	$select = $this->sql->select()
    	    ->columns(array(new Expression('count(*) as total')))
    	    ->join(array('ca' => 'cp_capturada')
    	    		, "ca.id_capturada = {$this->table}.id_capturada"
    	    , array()
    	    )
    	    ->where(array('id_tag' => $tag->getId()))
    	;
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna as capturadas tags de uma determinada tag
     * @param Tag $tag tag em questão
     */
    public function getCapturadasTag(Tag $tag, $limit = null, $offset = null)
    {
    
    	$select = $this->sql->select()
    	->join(array('ca' => 'cp_capturada')
    	    		, "ca.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
   	    )
    	->join(array('uc' => 'cp_usuario_capturada')
    	    		, "uc.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
   	    )
    	->where(array("{$this->table}.id_tag" => $tag->getId()))
    	->order("{$this->table}.id_capturada_tag ASC");

    	!$limit ?: $select->limit($limit);
    	!$offset ?: $select->offset($offset);
    
    	return $this->fetchAll($select);
    }
        
    /**
     * Método que retorna a capturada tag de uma determinada tag
     * @param Tag $tag tag em questão
     */
    public function getCapturada(Tag $tag, $limit = null, $offset = null)
    {
    
    	$select = $this->sql->select()
    	->join(array('ca' => 'cp_capturada')
    	    		, "ca.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
   	    )
    	->join(array('uc' => 'cp_usuario_capturada')
    	    		, "uc.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
   	    )
    	->where(array("{$this->table}.id_tag" => $tag->getId()))
    	->limit($limit)
    	->offset($offset);
    
    	return $this->fetchAll($select);
    }
        
    /**
     * Método que salva uma tag.
     * @param Tag $capturada Objeto de entidade de uma tag.
     */
    public function save(CapturadaTag $capturadaTag)
    {
    	if ($capturadaTag->getId() == 0) {
    		$this->insert($capturadaTag->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($capturadaTag->getId()) {
    		$this->update($capturadaTag->toArray(),array('id_capturada_tag' => $capturadaTag->getId(),));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
    /**
     * Método que remove um usuário.
     * @param User $user
     */
    public function deleteCapturadaTag(CapturadaTag $capturadaTag)
    {
    	$this->delete(array(
    	    'id_capturada_tag' => $capturadaTag->getId(),
    	));
    }
}
