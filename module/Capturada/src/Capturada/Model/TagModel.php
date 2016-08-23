<?php
namespace Capturada\Model;
use Common\Exception\TagVinculadaException;
use Capturada\Entity\CapturadaTag;
use Capturada\Entity\Tag;
use Capturada\Entity\UsuarioCapturada;
use User\Auth\CapturaAuth;
use Zend\Form\Form;
use Capturada\Table\CapturadaTable;
use Capturada\Entity\Capturada;
use Extended\Model\CapturaModel;
class TagModel extends CapturaModel
{

    /**
     * Método que é executando para listar as tags em uma busca por tags
     * e eventos.
     * @param Integer $pagina página da listagem de tags.
     * @param Integer $limit Quantidade de itens a serem retornados por vez.
     * @param Array $where itens de um where
     */
    public function findTags($pagina = null, $limit = 25, $where = null)
    {
        $tag = Tag::getNewInstance()->fillInArray($where);
        $offset = ($pagina * $limit) - $limit;
        $tags = $this->getTable('Tag', 'capturada')->findTags($tag, $limit, $offset);
        $retorno = array();
        foreach($tags as $tag){
            $obj = Tag::getNewInstance()->fillInArray($tag);
            $obj->quantidade = $this->getQuantidadeTag($obj);
            $capturadas = $this->getModel('CapturadaTag', 'capturada')->getCapturadasTag($obj,1,3);
            $obj->tag = $obj->nome;
            $obj->imagens = array();
            foreach($capturadas as $capturada)
            {
                 $obj->imagens[] = array(
                     'thumb' =>  $capturada->capturada->getThumb(),
                     'med' =>  $capturada->capturada->getImagem()
                 );
            }
            $retorno[] = $obj;
        }
        
        return $retorno;
    }
    
    /**
     * Método que retorna o total de itens no findTags.
     * @param Tag $tag
     */
    public function getTotalFindTags(Tag $tag)
    {
        return $this->getTable('Tag', 'capturada')->getTotalFindTags($tag);
    }
    
    /**
     * Método que retorna as tags de uma capturada
     * @param Integer $pagina página da listagem de tags.
     * @param Integer $limit Quantidade de itens a serem retornados por vez.
     */
    public function getTags($pagina = null, $limit = 25, $where = null)
    {
        $offset = ($pagina * $limit) - $limit;
        $tags = $this->getTable('Tag')->getTags($limit, $offset, $where, 'dt_tag DESC');
        $retorno = array();
        foreach($tags as $tag){
            $obj = new Tag();
            $obj->exchangeArray($tag);
            $obj->quantidade = $this->getQuantidadeTag($obj);
            $retorno[] = $obj;
        }

        return $retorno;
    }
    
    /**
     * Método que retorna as tags classificadas como evento.
     * @param Integer $pagina página do evento em questão.
     * @param Integer $limit Quantidade de itens a serem retornados por vez.
     */
    public function getEventos($pagina = null, $limit = 25)
    {
        return $this->getTags($pagina, $limit, array('evento' => '1'));
    }
    
    /**
     * Método que retorna uma tag
     * @param String $nome nome em minusculo da tag
     */
    public function getTag($nome)
    {
        $tag = $this->getTable('Tag')->findAll(array('nome' => strtolower($nome)));
        $retorno = new Tag();
        $tag->count() ? $retorno->exchangeArray($tag->current()) :  $retorno = false;
        return $retorno;
    }
    
    /**
     * Método que desvincula uma tag de uma capturada
     * @param Integer $idCapturada identificador da capturada
     * @param String $idTag identificador da tag
     */
    public function desvinculaTag($idCapturada, $idTag)
    {
        $retorno = $this->getTable('CapturadaTag')->findAll(array('id_tag' => $idTag, 'id_capturada' => $idCapturada));
        if($retorno->count()){
            $capturadaTag = new CapturadaTag();
            $capturadaTag->exchangeArray($retorno->current());
            $this->getTable('CapturadaTag')->deleteCapturadaTag($capturadaTag);
            return true;
        }
        return false;
    }
    
    /**
     * Método que retorna uma tag
     * @param String $nome nome em minusculo da tag
     */
    public function getTagLikeName($nome, $array = false)
    {
        $tags = $this->getTable('tag','capturada')->getTagLikeName($nome);
        if(!$tags->count()){
            return false;
        }
        $retorno = array();
        foreach($tags as $tag){
            if(!$array){
                $obj = new Tag();
                $obj->exchangeArray($tag);
                $retorno[] = $obj;
            } else {
                $retorno[] = $tag;
            }
        }
        return $retorno;
    }
    
    /**
     * Método que verifica se existe a tag, caso não exista
     * é adicionada ao banco.
     * @param Form $form
     * @return Integer retorna o id da tag.
     */
    public function adicionarTag($nomeTag, $evento = 0)
    {
    	$tag = new Tag();
    	$tag->exchangeArray(array('nome' => strtolower($nomeTag), 'evento' => $evento));
    	$retorno = $this->getTable('tag','capturada')->getTagPorNome($tag);
    	if(!$retorno->count()){
    		return $this->getTable('tag','capturada')->save($tag);
    	} else {
    		return $retorno->current()->id_tag;
    	}
    }
    
    /**
     * Método que vincula uma tag a capturada.
     * @param Integer $idTag Identificador da tag.
     * @param Integer $idCapturada Identificador da capturada.
     */
    public function vincularTag($idTag, $idCapturada)
    {
    	$capturadaTag = new CapturadaTag();
    	$capturadaTag->id_capturada = $idCapturada;
    	$capturadaTag->id_tag = $idTag;
    
    	$retorno = $this->getTable('capturadaTag','capturada')->getCapturadaTag($capturadaTag);
    	if($retorno->count()){
    		throw new TagVinculadaException();
    	}
    	$this->getTable('capturadatag','capturada')->save($capturadaTag);
    
    }
    
    /**
     * Método que vincula um array de tags para uma determinada Capturada
     * @param String $tags conjunto com nome das tags a serem inseridas, ou vinculadas.
     * @param Integer $idCapturada Identificador da Capturada.
     */
    public function vincularMultiplasTags($tags, $idCapturada)
    {
        if($tags){
        	$tags = explode(',',$tags);
        	foreach ($tags as $nomeTag){
        		$this->vincularTag($this->adicionarTag($nomeTag), $idCapturada);
        	}
        }
    }
    
    /**
     * Método que retorna a quantidade de tags
     */
    public function getQuantidadeTags($where = null)
    {
        return $this->getTable('Tag')->getQuantidadeTags($where);
    }
    
    /**
     * Método que retorna a quantidade de tags
     */
    public function getQuantidadeTag(Tag $tag)
    {
        return $this->getTable('CapturadaTag')->getQuantidadeTagsCapturada($tag);
    }
    
}
