<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;
use Facebook\src\Facebook;

use User\Entity\Window;

use User\Form\LoginForm;

use User\Auth\CapturaAuth;

use Common\Exception\SemPermissaoException;

use Extended\Controller\CapturaController;
use Zend\View\Model\ViewModel;
class IndexController extends CapturaController
{

    /**
     * Action que renderiza a pagina inicial
     */
    public function indexAction ()
    {
        $usuarioLogado = CapturaAuth::getInstance()->getUser();
        if($usuarioLogado){
        	$this->getModel('notificacao','capturada')->visualizarNovidades();
        }
        
//         $facebook = new Facebook(array(
//         		'appId' => '662887060405967',
//         		'secret' => 'b99bc88aefabe5ba9464968ce87d193e',
//         		'cookie' => 'true',
//         ));
//         $user = $facebook->getUser();
        
        if($this->params('error')){
            $semPermissao = new SemPermissaoException();
            $this->flashMessenger()->addMessage($this->getMsg($semPermissao->getMessage(), false));
        }
        $retorno = array();
        $limit = constant('PAGINACAO_NORMAL');
        $retorno['timeline'] = $this->getModel('Conteudo','User')->getTimeline($pagina = 1, $limit);
        return new ViewModel($retorno);
    }

    /**
     * Action que renderiza a pagina batalhas.
     */
    public function batalhasAction ()
    {
        $usuarioLogado = CapturaAuth::getInstance()->getUser();
        if($usuarioLogado){
        	$this->getModel('notificacao','capturada')->visualizarNovasCapturadasEmBatalha();
        }
        $retorno['batalhas'] = $this->getModel('batalha','capturada')->getBatalhasAtuais();
        return new ViewModel($retorno);
    }

    /**
     * Action que renderiza a pagina usuários.
     */
    public function capturadoresAction ()
    {   
        $retorno = array();
        $usuarioLogado = CapturaAuth::getInstance()->getUser();
        if($usuarioLogado){
        	$this->getModel('notificacao','capturada')->visualizarNovosUsuarios();
        }
        $retorno['usuarios'] = $this->getModel('user','user')->getMelhoresUsuarios($this->params('pagina'));
        return new ViewModel($retorno);
    }
    
    /**
     * Action que renderiza a pagina usuários.
     */
    public function videosAction ()
    {   
        $retorno = array();
        $retorno['videos'] = array(
            (object) array('titulo' =>'Fazendo o foco seu melhor amigo!', 'descricao' => 'Vídeo que mostra dicas sobre como controlar o foco quando ele se rebela contra você!', 'youtube' => 'https://www.youtube.com/v/iJwc-HtIn3o'),       
        );
        return new ViewModel($retorno);
    }
    
    /**
     * Action que renderiza a pagina usuários.
     */
    public function reunioesAction ()
    {
        $retorno = array();
        $retorno['pasta'] = '/documentos/';
        $retorno['atas'] = array(
            'ata-21-11-2012.pdf' => 'Reunião 21 novembro 2012',
            'ata-29-11-2012.pdf' => 'Reunião 29 novembro 2012',
            'ata-05-12-2012.pdf' => 'Reunião 05 dezembro 2012',
            'ata-16-01-2013.pdf' => 'Reunião 16 janeiro 2013',
            'ata-23-01-2013.pdf' => 'Reunião 23 janeiro 2013',
            'ata-30-01-2013.pdf' => 'Reunião 30 janeiro 2013',
            'ata-20-02-2013.pdf' => 'Reunião 20 fevereiro 2013',
            'ata-27-02-2013.pdf' => 'Reunião 27 fevereiro 2013',
            'ata-06-03-2013.pdf' => 'Reunião 06 março 2013',
            'ata-13-03-2013.pdf' => 'Reunião 13 março 2013',
            'ata-20-03-2013.pdf' => 'Reunião 20 março 2013',
            'ata-25-04-2013.pdf' => 'Reunião 25 abril 2013',
            'ata-01-05-2013.pdf' => 'Reunião 01 maio 2013',
            'ata-08-05-2013.pdf' => 'Reunião 08 maio 2013',
            'ata-29-05-2013.pdf' => 'Reunião 29 maio 2013',
        );
        return new ViewModel($retorno);
    }
    
    /**
     * Action que renderiza a pagina usuários.
     */
    public function sugestoesAction ()
    {
        $form    = $this->getForm('sugestao');
        $request = $this->getRequest();
        
        if($request->isPost())
        {
        	$form->setData($request->getPost()->toArray());
        	if ($form->isValid()) {
        	    
        	    $cal = $this->getModel('calendario','application')->getCalendarioByForm($form);
        	    $service = $this->getModel('calendario','application')->getServiceGoogleAgenda();
        	    $event = $service->getCalendarEventEntry($this->getModel('calendario','application')->getIdCapturaMe()); 
        	    $this->getModel('calendario','application')->inserirEventoCalendario($cal, $event, $service);
        	    if(CapturaAuth::getInstance()->exists()){
        	        $this->getModel('acaoUsuario','user')->pontoEnviarEvento(CapturaAuth::getInstance()->getUser()->id_usuario);
        	    }
       			$this->getModel('index','application')->enviarSugestao($form);
       			$this->flashMessenger()->addMessage($this->getMsg('Sugestão enviada com sucesso!'));
                return $this->redirect()->toRoute('home');
        	}
        }
        
        return new ViewModel(array('form' => $form));
    }
    public function adicionarEventoAction ()
    {
        $form    = $this->getForm('sugestao');
        $request = $this->getRequest();
        
        if($request->isPost())
        {
        	$form->setData($request->getPost()->toArray());
        	if ($form->isValid()) {
        	    
        	    $cal = $this->getModel('calendario','application')->getCalendarioByForm($form);
        	    $service = $this->getModel('calendario','application')->getServiceGoogleAgenda();
        	    $event = $service->getCalendarEventEntry($this->getModel('calendario','application')->getIdCapturaMe());
        	    $this->getModel('calendario','application')->inserirEventoCalendario($cal, $event, $service);
        	    $this->getModel('tag','capturada')->adicionarTag($cal->titulo, 1);
        	    if(CapturaAuth::getInstance()->exists()){
        	        $this->getModel('acaoUsuario','user')->pontoEnviarEvento(CapturaAuth::getInstance()->getUser()->id_usuario);
        	    }
       			$this->getModel('index','application')->enviarSugestao($form);
       			return $this->json('Evento criado com sucesso', true);
        	} else {
        		return $this->json($form->getErrorMessages(), false);
        	}
        }
        return $this->json('Não foi possível cadastrar o evento, tente mais tarde.', false);
    }
    
    /**
     * Action que renderiza a pagina info.
     */
    public function infoAction ()
    {
        // capturando um parametro do route $this->params('foto')
        return new ViewModel();
    }
    
    /**
     * Action que renderiza a pagina info.
     */
    public function quemSomosAction ()
    {
        // capturando um parametro do route $this->params('foto')
        return new ViewModel();
    }
    
    /**
     * Action que renderiza a pagina info.
     */
    public function resolucaoAction ()
    {
        
        Window::getInstance()->setWindow($this->getRequest()->getPost());
        
//         if(!isset($_COOKIE['WIDTH'])){
//             setcookie('WIDTH', $request->width);
//         }
//         if(!isset($_COOKIE['HEIGHT'])){
//             setcookie('HEIGHT', $request->height);
//         }
            
        return $this->json('sucesso', true);
    }

}
