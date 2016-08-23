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

class IndexController extends CapturaController
{
  
    /**
     * Ação de gerar uma capturada randômica
     */
    public function capturadaRandomicaAction()
    {
        $foto = $this->getModel('Index', 'Capturada')->getRandomCapturadas(1);
        FacebookNoticias::factory()->sendImage(current($foto));
    }
    
    /**
     * Action que mostra as thumbs no menu principal de usuário.
     * @return \Zend\View\Model\ViewModel
     */
    public function visualizarCapturadasAction()
    {
        $limit = constant('PAGINACAO_NORMAL');
        $usuario = $this->params('user');
        $pagina = $this->params('pagina');
        $retorno['pagina'] = $this->params('pagina');
        $retorno['quantidadeImagens'] = $this->getModel()->getQuantidadeCapturadas($usuario);
        $retorno['totalPaginas'] = ceil($retorno['quantidadeImagens']/$limit);
        
        if(!$retorno['quantidadeImagens']){
            $this->flashMessenger()->addMessage($this->getMsg('Usuário não enviou nenhuma foto ainda.', false));
            return $this->redirect()->toRoute('home');
        }
        
        if($retorno['totalPaginas'] < $pagina){
            $this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
            return $this->redirect()->toRoute('visualizar-capturadas', array('user' => $usuario, 'pagina' => 1));
        }
        
        $retorno['capturadas'] = $this->getModel()->getCapturadas($usuario, $pagina, $limit);
        $retorno['user'] = $this->getModel('User','User')->getUsuario($this->params('user'));
        if($this->params('capturada')){
        	$url = $retorno['capturadas'][$this->params('capturada')-1]->getImagem();
        	$this->getViewHelper('capturadaEscondidaHelper')->setUrlCapturadaEscondida($url);
        }
        return new ViewModel($retorno);
    }
    
    /**
     * Action que mostra as mais curtirdas do Captura.Me
     */
    public function maisCurtidasAction()
    {
        $limit = constant('PAGINACAO_NORMAL');
        $usuarioLogado = CapturaAuth::getInstance()->getUser();
        if($usuarioLogado){
            $this->getModel('notificacao','capturada')->visualizarMaisCurtidas();
        }
        $retorno['pagina'] = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $retorno['quantidadeImagens'] = $this->getModel('index', 'capturada')->getQuantidadeMaisCurtidas();
        $retorno['totalPaginas'] = ceil($retorno['quantidadeImagens']/$limit);
        
        if(!$retorno['quantidadeImagens']){
            $this->flashMessenger()->addMessage($this->getMsg('Erro inesperado.', false));
            return $this->redirect()->toRoute('home');
            
        }
        if($retorno['totalPaginas'] < $pagina){
            $this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
            return $this->redirect()->toRoute('mais-curtidas', array('pagina' => 1));
        }
        
        $retorno['capturadas'] = $this->getModel('index', 'capturada')->getMaisCurtidas($pagina, $limit);
        if($this->params('capturada')){
        	$url = $retorno['capturadas'][$this->params('capturada')-1]->getImagem();
        	$this->getViewHelper('capturadaEscondidaHelper')->setUrlCapturadaEscondida($url);
        }
        return new ViewModel($retorno);
    }
    
    /**
     * Action que mostra as capturadas mais novas no Captura.Me
     */
    public function novasCapturadasAction()
    {
        
        $usuarioLogado = CapturaAuth::getInstance()->getUser();
        if($usuarioLogado){
        	$this->getModel('notificacao','capturada')->visualizarNovasCapturadas();
        }
        
        $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina'] = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $retorno['quantidadeImagens'] = $this->getModel('index', 'capturada')->getQuantidadeTotalCapturadas();
        $retorno['totalPaginas'] = ceil($retorno['quantidadeImagens']/$limit);

        if(!$retorno['quantidadeImagens']){
            $this->flashMessenger()->addMessage($this->getMsg('Erro inesperado.', false));
            return $this->redirect()->toRoute('home');
            
        }
        if($retorno['totalPaginas'] < $pagina){
            $this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
            return $this->redirect()->toRoute('mais-curtidas', array('pagina' => 1));
        }
        
        $retorno['capturadas'] = $this->getModel('index', 'capturada')->getNovasCapturadas($pagina, $limit);
        if($this->params('capturada')){
            $url = $retorno['capturadas'][$this->params('capturada')-1]->getImagem();
            $this->getViewHelper('capturadaEscondidaHelper')->setUrlCapturadaEscondida($url);
        }
        return new ViewModel($retorno);
    }
    
    /**
     * Método que mostra a foto em tamanho original
     */
    public function visualizarCapturadaAction()
    {
        $capturadas = $this->getModel()->getVisualizacaoCapturada($this->params('user'), $this->params('capturada'));
        $retorno['capturada'] = $imagemAtual = $capturadas[0];
        $imagemSeguinte = count($capturadas) > 1 ? $capturadas[1] : false;
        $retorno['voltar'] = $imagemAtual->id_capturada_anterior ? "{$this->params('user')}/{$imagemAtual->id_capturada_anterior}" : false;
        $retorno['avancar'] = $imagemSeguinte ? "{$this->params('user')}/{$imagemSeguinte->id_capturada}" : false;
        $retorno['user'] = $this->getModel('User','User')->getUsuario($this->params('user'));
        $retorno['tags'] = $this->getModel('index','capturada')->getTags($this->params('capturada'));
        return new ViewModel($retorno);
    }
    
    public function getCapturadaEscondidaAction()
    {
        $capturadas = $this->getModel()->getVisualizacaoCapturada($this->getRequest()->getPost('user'), $this->getRequest()->getPost('capturada'));
        if(count($capturadas) > 0){
            return $this->json($capturadas[0]->capturada->getImagem(), true);
        }
        return $this->json("erro", false);
        
    }
    
    
    /**
     * Método cadsatra um voto positivo na imagem
     */
    public function pegueiImagemCapturadaAction()
    {
        
        $retorno = array();
        $post = explode('_', $this->getRequest()->getPost('usuarioCapturada'));
        $capturada = $post[0];
        $usuario = $post[1];
        $retorno = $this->getModel('index', 'capturada')->getCapturada($capturada, $usuario);
        $pontos = $this->getModel('index', 'capturada')->setPegou($capturada, $usuario);
        
        if(CapturaAuth::getInstance()->exists()){
            $this->getModel('acaoUsuario','user')->pontoCurtir(CapturaAuth::getInstance()->getUser()->id_usuario);
        }
        
        return $this->json(array('msg' =>'Peguei cadastrado', 'pontos' => $pontos), true);
    }
    
    /**
     * Método cadsatra um voto negativo na imagem
     */
    public function naoPegueiImagemCapturadaAction()
    {
        
        $retorno = array();
        $post = explode('_', $this->getRequest()->getPost('usuarioCapturada'));
        $capturada = $post[0];
        $usuario = $post[1];
        $retorno = $this->getModel('index', 'capturada')->getCapturada($capturada, $usuario);
        $pontos = $this->getModel('index', 'capturada')->setNaoPegou($capturada, $usuario);
        
        if(CapturaAuth::getInstance()->exists()){
            $this->getModel('acaoUsuario','user')->pontoCurtir(CapturaAuth::getInstance()->getUser()->id_usuario);
        }
        
        return $this->json(array('msg' =>'Não Peguei cadastrado', 'pontos' => $pontos), true);
    }
    
    /**
     * Método faz o download da imagem original
     */
    public function downloadCapturadaAction()
    {
        
    	$retorno = array();
    	$request = $this->getRequest();
    	$retorno = $this->getModel('index', 'capturada')->getCapturada($request->getPost('capturada'), $request->getPost('usuario'));
    	
    	header('Set-Cookie: fileDownload=true; path=/');
    	if(preg_match('/android/i', $_SERVER['HTTP_USER_AGENT'])) {
    	    header('Content-Type: application/vnd.android.package-archive');
    	} else {
        	header("Content-Type: application/save");
    	}
    	header("Content-Length:".filesize($retorno->getPath()));
    	header('Content-Disposition: attachment; filename="capturada' . $request->getPost('capturada') . '.jpg"');
    	header("Content-Transfer-Encoding: binary");
    	header('Expires: 0');
    	header('Pragma: no-cache');
    
    	// nesse momento ele le o arquivo e envia
    	ob_clean();
    	flush();
    	readfile($retorno->getPath());
    	
    	return true;
    	
    }
    /**
     * Action que edita uma capturada.
     */
    public function editarCapturadaAction()
    {
        if($this->params('user') != CapturaAuth::getUser()->id_usuario){
            throw new SemPermissaoException();
        }
        $form    = $this->getForm('editarCapturada');
        $request = $this->getRequest(); // Capturando o request
        $retorno['capturada'] = $capturada = $this->getModel()->getCapturada($this->params('capturada'), $this->params('user'));
        
        if(!$capturada){
            $this->flashMessenger()->addMessage($this->getMsg('Capturada selecionada pertence a outro usuário.',false));
            return $this->redirect()->toRoute('home');
        }
        $retorno['user'] = $this->getModel('User','User')->getUsuario($this->params('user'));
        $retorno['form'] = $form;
        
        if($request->isPost())
        {
       	    $form->setData(array_merge(array('id_capturada' => $retorno['capturada']->id_capturada), $request->getPost()->toArray()));
            if ($form->isValid()) {
           	    $this->getModel('Index', 'Capturada')->alterarCapturada($form);
           	    return $this->redirect()->toRoute('home');
            }
        } else {
            $form->setData($retorno['capturada']->toArray());
        }
        
        return new ViewModel($retorno);
    }
    
    /**
     * Action que adiciona uma tag a uma capturada.
     * @return \Zend\View\Model\ViewModel
     */
    public function adicionarTagAction()
    {
        $form =  $this->getForm('adicionarTag');
        $request = $this->getRequest();
        $retorno['capturada'] = $this->getModel()->getCapturada($this->params('capturada'), $this->params('user'));
     	$retorno['user'] = $this->getModel('User','User')->getUsuario($this->params('user'));
       	$retorno['form'] = $form;
        
        if($request->isPost())
        {
        	$file    = $this->params()->fromFiles('arquivo');
        	$form->setData($request->getPost()->toArray());
        	if ($form->isValid()) {
        	    try {
            	    // verifica se existe no banco, existindo deve adicionar uma nova tag na cp_tag
            	    $idTag = $this->getModel('index','capturada')->adicionarTag($form);
            	    
             	    // vincular a tag a id_capturada
        	        $this->getModel('index','capturada')->vincularTag($idTag, $this->params('capturada'));
        	    
        	        $this->flashMessenger()->addMessage($this->getMsg('Adicionada tag com sucesso!'));
        	        return $this->redirect()->toRoute('visualizar-capturada', array('user' => $this->params('user'), 'capturada' => $this->params('capturada')));
        	    } catch(TagVinculadaException $e){
        	        $this->flashMessenger()->addMessage($this->getMsg($e->getMessage(), false));
        	        return $this->redirect()->toRoute('visualizar-capturada', array('user' => $this->params('user'), 'capturada' => $this->params('capturada')));
        	    }
        	} 
        } 
        return new ViewModel($retorno);
    } 
    
}