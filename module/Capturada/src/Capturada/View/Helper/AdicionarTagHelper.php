<?php
namespace Capturada\View\Helper;
use Extended\View\CapturaViewHelper;

class AdicionarTagHelper extends CapturaViewHelper
{
	public function __invoke($idCapturada, $idUsuario)
	{
	    $config = array('capturada' => $idCapturada, 'usuario' => $idUsuario);
        return $this->renderPartial('adicionar-tag', $config, 'Capturada', 'Tag');
	}
}