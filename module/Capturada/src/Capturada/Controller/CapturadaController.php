<?php
namespace Capturada\Controller;
use Extended\Object\FacebookNoticias;

use Common\Exception\SemArquivoException;

use Common\Exception\ArquivoNaoInseridoException;

use Common\Exception\SessaoMortaException;

use Facebook\src\FacebookApiException;

use Facebook\src\Facebook;

use Zend\Stdlib\Parameters;

use Zend\Http\Request;
use Common\Exception\TagVinculadaException;
use Common\Exception\SemPermissaoException;
use Capturada\Model\IndexModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Extended\Controller\CapturaController;
use Common\Exception\CapturadaExistenteException;
use Common\Exception\ArquivoCapturadaExistenteException;
use Common\Exception\ExtensaoArquivoCapturadaInvalidoException;
use \User\Auth\CapturaAuth;

class CapturadaController extends CapturaController
{
    
       
    public function adicionarCapturadaAction()
    {
        
        $request = $this->getRequest()->getPost(); // Capturando o request
        
        $file = $this->getFiles('file');
        
        try{
            
            // verifica se o usuário deslogou sem querer
            $this->getModel('Capturada','Capturada')->verificaSessao();

            // verifica se existe o arquivo enviado 
            $this->getModel('Capturada','Capturada')->verificaArquivo($file);

            // Criando o objeto capturada
            $capturada = $this->getModel('Capturada','Capturada')->createCapturaObject($file, $request);
            
            // verificar a extensão
            $this->getModel('Capturada','Capturada')->verificaExtensao($file);
            
            // verificar se a capturada já foi inserida
            $this->getModel('Capturada','Capturada')->existeCapturada($capturada);
            
            // inserir  no banco
            $this->getModel('Capturada','Capturada')->salvar($capturada);

           	// Vinculação de todas as tags para a capturada
            $this->getModel('tag','capturada')->vincularMultiplasTags($request->tags, $capturada->id_capturada);
            
            // copiar arquivo
            $this->getModel('Capturada','Capturada')->copiarCapturada($capturada);
            
            // Gera a thumb e medianas padrões
            $this->getModel('Capturada','Capturada')->gerarImagensExtras($capturada);
            
            // inserindo o ponto por envio de uma capturada
           	$this->getModel('acaoUsuario','user')->pontoEnviarCapturada(CapturaAuth::getInstance()->getUser()->id_usuario);
                        
            return $this->json('adicionado com sucesso', true);
            
            
        } catch (SemArquivoException  $e){
            return $this->json($e->getMessage(), false);
        } catch (CapturadaExistenteException  $e){
            return $this->json($e->getMessage(), false);
        } catch (ExtensaoArquivoCapturadaInvalidoException  $e){
            return $this->json($e->getMessage(), false);
        } catch (SessaoMortaException $e){
            return $this->json($e->getMessage(), false); 
        } catch (ArquivoNaoInseridoException $e){
            return $this->json($e->getMessage(), false);
        } 
        
    }
    
}