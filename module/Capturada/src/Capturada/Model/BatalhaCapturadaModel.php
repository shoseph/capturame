<?php
namespace Capturada\Model;

/**
 * Class que contém as regras de negócio de uma batalha.
 */
use Capturada\Entity\Capturada;

use Capturada\Entity\BatalhaCapturada;

use Extended\Model\CapturaModel;

class BatalhaCapturadaModel extends CapturaModel
{
    
    /**
     * Método carrega as imagens que estão em uma determinada batalha
     * @param Form $capturada formulário de uma nova batalha 
     * @throws \Common\Exception\CapturadaExistenteException
     */
    public function getImagensBatalha($idBatalha, $pagina, $limit = 25)
    {
        $retorno = array();
        $offset = ($pagina * $limit) - $limit;
        $capturadas = $this->getTable('batalhaCapturada','capturada')->getCapturadasNaBatalha($idBatalha, $limit, $offset);
        
        foreach($capturadas as $capturada){
            $bc = BatalhaCapturada::getInstance()->fillInArray($capturada);
            $cap = Capturada::getInstance()->fillInArray($capturada);
            $cap->tags = $this->getModel('index','capturada')->getTags($capturada->id_capturada);
            $bc->setCapturada($cap);
            $bc->capturada->user = $capturada->id_usuario;
            $bc->setPegou($this->getTable('batalhaPonto')->getPegouCapturada($bc->id_batalha_capturada));
            $bc->setNaoPegou($this->getTable('batalhaPonto')->getNaoPegouCapturada($bc->id_batalha_capturada));
            $retorno[] = $bc; 
        }
        return $retorno;
        
    }
    
}
