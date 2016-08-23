<?php
namespace Capturada\Model;
use Capturada\Entity\CapturadaTag;

use Capturada\Entity\Capturada;

use Capturada\Entity\Tag;

use Extended\Model\CapturaModel;
class CapturadaTagModel extends CapturaModel
{
   
    /**
     * Método que retorna a quantidade de tags
     */
    public function getQuantidadeCapturadaTag(Tag $tag)
    {
        return $this->getTable('CapturadaTag')->getQuantidadeTagsCapturada($tag);
    }
    
    /**
     * Método que retorna a quantidade de tags
     */
    public function getCapturadasTag(Tag $tag, $pagina, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $capturadas =  $this->getTable('CapturadaTag')->getCapturadasTag($tag, $limit, $offset);
        $retorno = array();
        $contador = 1;
        foreach($capturadas as $capturada){
            $capTag = new CapturadaTag();
        	$cap = new Capturada();
        	$cap->user = $capturada->id_usuario;
        	$cap->tags = $this->getModel('index','capturada')->getTags($capturada->id_capturada);
        	$capTag->numero = $limit * ($pagina -1) + $contador;
        	$cap->exchangeArray($capturada);
        	$capTag->exchangeArray($capturada);
        	$capTag->setCapturada($cap);
        	$retorno[] = $capTag;
        	$contador++;
        }
        return $retorno;
    }

    /**
     * Método que retorna a quantidade de tags
     */
    public function getCapturadaTag(Tag $tag, $pagina, $limit = 25)
    {
        $offset = (int)($pagina * $limit) - $limit;
        $capturada =  $this->getTable('CapturadaTag')->getCapturada($tag, $limit, $offset);
        if($capturada->count()){
            $capturada = $capturada->current();
            $cap  = new Capturada();
            $cap->user = $capturada->id_usuario;
            $cap->exchangeArray($capturada);
            $capTag = new CapturadaTag();
            $capTag->exchangeArray($capturada);
            $capTag->setCapturada($cap);
            return $capTag;
        }
        return false;
    }
    
}
