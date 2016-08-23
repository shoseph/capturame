<?php
namespace Capturada\View\Helper;
use Extended\View\CapturaViewHelper;

class TagsHelper extends CapturaViewHelper
{
	public function __invoke($idCapturada, $tags, $usuario)
	{
	    $config = array('capturada' => $idCapturada, 'tags' => $tags, 'usuario'=> $usuario);
        return $this->renderPartial('tags', $config, 'Capturada', 'Tag');
	}
}