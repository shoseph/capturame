<?php
namespace Application\View\Helper;

use User\Entity\TipoConteudo;

use User\Entity\Conteudo;

use Extended\View\CapturaViewHelper;

class NoticiasHelper extends CapturaViewHelper
{
    public function __invoke ()
    {
        $dica= $this->getModel('Conteudo','User')->getUltimoConteudo($this->getModel('Conteudo','User')->getTipoConteudo('dica'));
        $artigo= $this->getModel('Conteudo','User')->getUltimoConteudo($this->getModel('Conteudo','User')->getTipoConteudo('artigo'));
        $novidade= $this->getModel('Conteudo','User')->getUltimoConteudo($this->getModel('Conteudo','User')->getTipoConteudo('novidade'));
        return $this->renderPartial('bloco-noticias', array('novidade' => $novidade, 'artigo' => $artigo, 'dica' => $dica), 'Application', 'Index');
    }
    
}