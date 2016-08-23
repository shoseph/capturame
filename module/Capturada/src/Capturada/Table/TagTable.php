<?php
namespace Capturada\Table;
use Zend\Db\Sql\Expression;

use Capturada\Entity\Tag;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
class TagTable extends CapturaTable{
    
    protected $table ='cp_tag';
    protected $key = 'id_tag';
    
    /**
     * Método que retorna uma tag.
     * @param Tag Objeto do tipo tag
     */
    public function getTagPorNome($tag)
    {
        $select = $this->sql->select()
        		->where(array('nome' => $tag->nome))
        ;
        return $this->fetchAll($select);
    }
    
    public function getTagLikeName($nome)
    {
        $select = $this->sql->select()
        		->where(array("nome like ?" => "%{$nome}%"))
        ;
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna todas as tags de um determinado
     * espaço determinado.
     * @param Tag $tag objeto do uma tag
     */
    public function findTags(Tag $tag, $limit, $offset)
    {
        $select = $this->sql->select()
        		->where(array("nome like ?" => "%{$tag->nome}%"))
        		->limit($limit)
        		->offset($offset)
        ;
        $tag->evento ? $select->where(array("evento = 1")) : ''; 
        //: $select->where(array("(isnull(evento) or evento = 0)"));

        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de tags.
     * @param Integer $idUsuario Identificador de um usuário.
     */
    public function getTotalFindTags(Tag $tag)
    {
        $select = $this->sql->select()
        ->columns(array(new Expression('count(*) as total')))
        ->where(array("nome like ?" => "%{$tag->nome}%"))
        ;
        $tag->evento ? $select->where(array("evento = 1")) : $select->where(array("(isnull(evento) or evento = 0)"));
        
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna uma tag.
     * @param Tag Objeto do tipo tag
     */
    public function getTag(Tag $tag)
    {
        $select = $this->sql->select()
        		->where($tag->toArray())
        ;
        
        return $this->fetchAll($select);
    }
    /**
     * Método que retorna uma tag.
     * @param Tag Objeto do tipo tag
     */
    public function getTags($limit, $offset, $where, $order)
    {
        $select = $this->sql->select()
        		->where($where)
                ->limit($limit)
                ->offset($offset)
                ->order($order)
        ;
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de tags.
     * @param Integer $idUsuario Identificador de um usuário.
     */
    public function getQuantidadeTags($where = null)
    {
    	$select = $this->sql->select()->columns(array(new Expression('count(*) as total')));
    	!$where ?: $select->where($where); 
    	
    	return (int) $this->fetchAll($select)->current()->total;
    }
        
    /**
     * Método que salva uma tag.
     * @param Tag $capturada Objeto de entidade de uma tag.
     */
    public function save(Tag $tag)
    {
    	if ($tag->getId() == 0) {
    		$this->insert($tag->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($tag->getId()) {
    		$this->update($tag->toArray(),array('id_tag' => $tag->getId(),));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
}
