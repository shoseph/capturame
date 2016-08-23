<?php
namespace Capturada\Controller;
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

class TagController extends CapturaController
{
        
    /**
     * Action que mostra quais as tags que tem no Captura.Me
     */
    public function listarTagsAction()
    {
        $retorno = array();
        $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina']  = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $retorno['quantidadeTags'] = $this->getModel()->getQuantidadeTags();
        $retorno['totalPaginas'] = ceil($retorno['quantidadeTags']/$limit);
        
        if($retorno['totalPaginas'] < $pagina){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('listar-tags', array('pagina' => 1));
        }
        $retorno['tags'] = $this->getModel()->getTags($pagina,$limit);
        return new ViewModel($retorno);
    } 
    
    
    public function getTagAction()
    {
        $nomeTag = $this->getRequest()->getPost('nome');
        $tags = $this->getModel()->getTagLikeName($nomeTag, true);
        return $tags ? $this->json($tags,true) : $this->json(false,false);
    }
    
    public function desvincularTagAction()
    {
        $idCapturada = $this->getRequest()->getPost('capturada');
        $idTag = $this->getRequest()->getPost('tag');
        $resposta = $this->getModel()->desvinculaTag($idCapturada, $idTag);
        return $resposta ? $this->json(true,true) : $this->json(false,false);
    }

    /**
     * Método que vincula uma tag a uma capturada
     */
    public function addTagAction()
    {
        try 
        {
            $request = $this->getRequest();
            if($this->getRequest()->isPost()){
                $nomeTag = $this->getRequest()->getPost('nome'); 
            	// verifica se existe no banco, existindo deve adicionar uma nova tag na cp_tag
            	$idTag = $this->getModel()->adicionarTag($nomeTag);
            	 
            	// vincular a tag a id_capturada
            	$this->getModel()->vincularTag($idTag, $this->getRequest()->getPost('capturada'));
            	return $this->json(array('msg' =>"Tag {$nomeTag} Adicionada", 'tag' => $idTag), true);
            }
        } catch(TagVinculadaException $e){
           	return $this->json(array('msg' => $e->getMessage()), false);
        }
        
       	return $this->json(array('msg' =>"Não foi possível adicionar a tag, tente mais tarde."), false);
    }
    
    /**
     * Action que lista os itens de uma tag
     */
    public function listarTagAction()
    {
        $retorno = array();
        // TODO: refazer para que o paginadorHelper tenha esse dado tratado
        $retorno['limit'] = $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina']  = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        
        // montar objeto tag
        $retorno['tag'] = $tag = $this->getModel()->getTag($this->params('nome'));
        
        // buscar quantidade total de imagens nessa tag
        $retorno['quantidadeCapturadas'] = $quantidadeCapturadas = $this->getModel('CapturadaTag')->getQuantidadeCapturadaTag($tag);
        $retorno['totalPaginas'] = ceil($retorno['quantidadeCapturadas']/$limit);
        
        // verificar se possui imagens para essa tag
        if($retorno['totalPaginas'] < $pagina){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('listar-tags', array('pagina' => 1));
        }
        
        // criar objeto capturadaTag        
        $retorno['capturadas'] = $this->getModel('CapturadaTag')->getCapturadasTag($tag, $pagina, $limit);
        if($this->params('capturada')){
        	$url = $retorno['capturadas'][$this->params('capturada')-1]->capturada->getImagem();
        	$this->getViewHelper('capturadaEscondidaHelper')->setUrlCapturadaEscondida($url);
        }
        
        return new ViewModel($retorno);
    }
    
    /**
     * Action que lista os itens de uma tag
     */
    public function buscarTagsAction()
    {
        
        $retorno = array();
        $retorno['limit'] = $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina']  = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        
        // montar objeto tag
        $retorno['tags'] = $tag = $this->getModel()->findTags($retorno['pagina'], $retorno['limit'], $this->getRequest()->getPost());
        if($tag){
            // buscar quantidade total de imagens nessa tag
            $retorno['quantidadeCapturadas'] = $quantidadeCapturadas = $this->getModel()->getTotalFindTags($tag[0]);
            $retorno['totalPaginas'] = ceil($retorno['quantidadeCapturadas']/$limit);
        
        } else {
            $retorno['quantidadeCapturadas'] = $retorno['totalPaginas'] = 0; 
        }
        
        // verificar se possui imagens para essa tag
        if($retorno['totalPaginas'] < $pagina){
            return $this->json('Não existe item', false);
        }
        
        return $this->json($retorno, true);
    }
    
    /**
     * Action que visualiza todas as tags 
     */
    public function visualizarTagAction()
    {
        $retorno = array();
        $retorno['limit'] = $limit = 1;
        $retorno['pagina']  = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        
        // montar objeto tag
        $retorno['tag'] = $tag = $this->getModel()->getTag($this->params('nome'));
        
        // criar objeto capturadaTag        
        $retorno['capturada'] = $this->getModel('CapturadaTag')->getCapturadaTag($tag, $pagina, $limit);
        
        // retornando quem é o usuário da capturada
        $retorno['user'] = $this->getModel('User','User')->getUsuario($retorno['capturada']->capturada->user);

        // retornando as outras tags da imagem
        $retorno['tags'] = $this->getModel('index','capturada')->getTags($retorno['capturada']->capturada->getId());
        
        $anterior = $pagina-1; $proxima = $pagina+1;
        $retorno['voltar'] = $pagina == 1 ? false : "{$tag->nome}/{$anterior}";
        
        $quantidadeCapturadas = $this->getModel('CapturadaTag')->getQuantidadeCapturadaTag($tag);
        $retorno['avancar'] = $pagina >= $quantidadeCapturadas ? false : "{$tag->nome}/{$proxima}";
        
        // verificar se possui imagens para essa tag
        if(!$retorno['capturada']){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('listar-tags', array('pagina' => 1));
        }
        
        return new ViewModel($retorno);
    }
}
