<?php
namespace Application\Controller;
use Extended\Controller\CapturaController;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CaptchaController extends CapturaController
{
    public function gerarCaptchaAction ()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");
        $id = $this->params('id', false);
        if ($id){
            $image = './data/captcha/' . $id;
            if (file_exists($image) !== false){
                $fp = fopen($image, "r");
                $imageread = fpassthru($fp);
                $response->setStatusCode(200);
                $response->setContent($imageread);
                
                // TODO: verificar depois o que o unlink faz, pois o mesmo está deletando o arquivo em prod
//                 if (file_exists($image) == true){
//                     unlink($image);
//                 }
            }
        }
        return $response;
    }
}