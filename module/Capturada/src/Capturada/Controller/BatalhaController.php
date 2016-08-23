<?php
namespace Capturada\Controller;
use Capturada\Entity\Notificacao;

use Common\Exception\PontoCadastradoException;

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

class BatalhaController extends CapturaController
{
    public function cadastrarBatalhaAction()
    {
        $retorno = array();
        $form = $this->getForm('cadastrarBatalha');
        $request = $this->getRequest(); // Capturando o request
        if($request->isPost())
        {
        	$form->setData($request->getPost()->toArray());
        	if ($form->isValid()) {
        		$this->getModel()->cadastrarBatalha($form);
        		$this->flashMessenger()->addMessage($this->getMsg('Batalha cadastrada com sucesso!'));
        		return $this->redirect()->toRoute('home');
        	} 
        }
        $retorno['form'] = $form;
        return new ViewModel($retorno);
    }

    /**
     * Método que liga o modo batalha
     */
    public function batalheAction()
    {
        CapturaAuth::getInstance()->getUser()->ligarModoBatalha();
        return $this->redirect()->toRoute('home');
    }
    
    /**
     * Método que desliga o modo batalha
     */
    public function naoBatalheAction()
    {
        CapturaAuth::getInstance()->getUser()->desligarModoBatalha();
        return $this->redirect()->toRoute('home');
    }
    
    /**
     * Action que cadastra uma capturada em uma batalha
     */
    public function cadastrarCapturadaBatalhaAction()
    {
        // mostrar uma listagem de fotos que ele possui, onde as imagens vistas não podem ser cadastradas em outra batalha
        $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina'] = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
        
        
        // total de imagens que ele tem para mostrar
        $retorno['totalImagens'] = $this->getModel()->getQuantidadeMaximaEmBatalha($usuario);
        
        // total de paginas
        $retorno['totalPaginas'] = ceil($retorno['totalImagens']/$limit);
        
        // verificando se o usuário tem alguma foto para batalhar
        if($retorno['totalPaginas'] == 0){
            $this->flashMessenger()->addMessage($this->getMsg('Não existe fotos disponíveis, envie mais fotos!', false));
            return $this->redirect()->toRoute('home');
        }
        
        // verificando se existe uma pagina inexistente
        if($retorno['totalPaginas'] < $pagina){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('home');
        }
        
        // Captura todas as imagens não cadastradas em uma batalha
        $retorno['capturadas'] = $this->getModel()->getCapturadasNaoCadastradas($usuario, $pagina, $limit);
        $retorno['user'] = CapturaAuth::getInstance()->getUser();
        
        $this->getModel('batalha','capturada')->setObjBatalhasNoJavascript();
        
        $retorno['batalhas'] = $this->getModel('batalha','capturada')->getBatalhasAtuais();
        return new ViewModel($retorno);
    }
    
    /**
     * Método que seleciona uma capturada para uma batalha.
     */
    public function selecionarCapturadaAction()
    {

        $usuario = CapturaAuth::getInstance()->getUser()->id_usuario;
        $capturada = $this->getRequest()->getPost('capturada');
        $batalha = $this->getRequest()->getPost('batalha');
        
        $batalhaSelecionada = $this->getModel()->getBatalha($batalha);
        if($this->getModel()->getQuantidadeCapturadasEmBatalhaAtual($usuario, $batalha) >= $batalhaSelecionada->quantidade){
            return $this->json($this->getMsg('Não é possível cadastrar mais fotos nesta batalha',false), false);
        }
        
        try{
            $this->getModel()->cadastrarCapturadaEmBatalha($capturada, $batalha, $usuario);
            // inserindo o ponto por envio de uma capturada
            $this->getModel('acaoUsuario','user')->pontoEscolherBatalha(CapturaAuth::getInstance()->getUser()->id_usuario);
        } catch(\Exception $e){
            return $this->json($this->getMsg('Não foi possível cadastrar essa imagem na batalha, tente mais tarde.',false), false);
        }
       return $this->json($this->getMsg('Imagem cadastrada com sucesso!.'), true);
    }
        
    /**
     * Action que mostra a batalha selecionada
     */
    public function visualizarBatalhaAction()
    {

        $limit = constant('PAGINACAO_NORMAL');
        $batalha = $this->params('batalha') ? $this->params('batalha') : 1;
        $retorno['pagina'] = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $retorno['quantidadeImagens'] = $this->getModel()->getQuantidadeTotalCapturadasEmBatalha($batalha);
        $retorno['totalPaginas'] = ceil($retorno['quantidadeImagens']/$limit);
        
        if(!$retorno['quantidadeImagens']){
        	$this->flashMessenger()->addMessage($this->getMsg('Não existe capturadas nessa batalha..', false));
        	return $this->redirect()->toRoute('home');
        
        }
        
        if($retorno['totalPaginas'] < $pagina){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('home');
        }
        
        // procurar as imagens da batalha
        $retorno['batalhaCapturada'] = $this->getModel('Batalha', 'Capturada')->getCapturadaNaBatalha($batalha, $pagina, $limit);                
        if($this->params('capturada')){
        	$url = $retorno['batalhaCapturada'][$this->params('capturada')-1]->capturada->getImagem();
        	$this->getViewHelper('capturadaEscondidaHelper')->setUrlCapturadaEscondida($url);
        }
        
        return new ViewModel($retorno);
    }
    
    /**
     * Método que cadastra um peguei
     * @return \Zend\View\Model\JsonModel
     */
    public function pegueiCapturadaAction()
    {
        $usuario = CapturaAuth::getUser() ? CapturaAuth::getUser() : false;
        $batalha = $this->getRequest()->getPost('batalha');
        
        try{
            $pontos = (!$usuario) ? $pontos = $this->getModel()->cadastrarPegueiVisitante($batalha) : $this->getModel()->cadastrarPegueiUsuario($batalha, $usuario);
            if(CapturaAuth::getInstance()->exists()){
            	$this->getModel('acaoUsuario','user')->pontoCurtir(CapturaAuth::getInstance()->getUser()->id_usuario);
            }
        } catch(PontoCadastradoException $e){
            return $this->json(array('msg' => $e->getMessage(), 'pontos' => 0), false);
        }
        
        return $this->json(array('msg' =>'Peguei cadastrado', 'pontos' => $pontos), true);
        
    }
    
    /**
     * Método que cadastra um não peguei
     * @return \Zend\View\Model\JsonModel
     */
    public function naoPegueiCapturadaAction()
    {
        $usuario = CapturaAuth::getUser() ? CapturaAuth::getUser() : false;
        $batalha = $this->getRequest()->getPost('batalha');
        
        try{
        	$pontos = (!$usuario) ? $pontos = $this->getModel()->cadastrarNaoPegueiVisitante($batalha) : $this->getModel()->cadastrarNaoPegueiUsuario($batalha, $usuario);
        	if(CapturaAuth::getInstance()->exists()){
        		$this->getModel('acaoUsuario','user')->pontoCurtir(CapturaAuth::getInstance()->getUser()->id_usuario);
        	}
        } catch(PontoCadastradoException $e){
        	return $this->json(array('msg' => $e->getMessage(), 'pontos' => 0), false);
        }
        return $this->json(array('msg' =>'Não Peguei cadastrado', 'pontos' => $pontos), true);
        
    }
}