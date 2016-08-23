<?php

namespace UserTest\Controller;

use Zend\Stdlib\Parameters;

use User\Entity\User;

use User\Auth\CapturaAuth;
use Zend\Mvc\Application;
use UserTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class UserControllerTest extends PHPUnit_Framework_TestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp ()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \User\Controller\UserController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'user'));
        
        $config = $serviceManager->get('Config');

        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        
        $this->event = new MvcEvent();
        $this->event->setTarget($this->controller);
        $this->event->setApplication(new Application($config, $serviceManager));
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);

        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }
    
    /**
     * Método que testa a ação de index com o usuario "visitante".
     * @expectedException Common\Exception\SemPermissaoException
     */
    public function testAcessoUsuarioVisitanteIndexAction()
    {
         $this->routeMatch->setParam('action', 'index');
         $this->controller->dispatch($this->request);
    }
    
    /**
     * Método que testa a ação de index com o usuario "usuario".
     */
    public function testAcessoUsuarioUsuarioIndexAction()
    {
         $this->routeMatch->setParam('action', 'index');
         $capturador = (object) array( 'id_usuario' => 1, 'tipo' => 'usuario', 'nome' => 'usuario teste','login' => 'teste.login', 'senha' => 'bb52d1f60e462bce604261c389de7eae', 'cpf' => '07228983475', 'email' => 'no-reply@captura.me', 'ativo' => 1,);
         CapturaAuth::getInstance()->setUser($capturador);
         $this->controller->setUser(CapturaAuth::getInstance());
         $retorno = $this->controller->dispatch($this->request);
         $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }

    /**
     * Método que faz o tes de acesso a funcionalidade, cadastrando
     * um usuário novo.
     */
    public function testAcessarRegistrarAction()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        
        $this->request->setPost( new Parameters(array(
        		'nome' => 'Usuario Teste',
        		'login' => 'd9afbf73c5231e8d68c5d22b7e60e52d',
        		'senha' => 'teste1234',
        		'verifysenha' => 'teste1234',
        		'email' => 'no-reply@captura.me',
        		'cpf' => '115.854.253-46',
        )));
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * Método que tenta quebrar o registro informando apenas
     * o nome.
     */
    public function testRegistrarApenasNomeAction ()
    {
       // d9afbf73c5231e8d68c5d22b7e60e52d = md5 de "usuarioteste"
       $this->routeMatch->setParam('action', 'registrar');
       $this->request->setMethod('POST');
       $this->request->setPost( new Parameters(array(
       		'nome' => 'Usuario Teste',
       )));
       $return = $this->controller->dispatch($this->request);
       $this->assertSame(array('login','verifysenha', 'email'), array_keys($return->form->getMessages()));
       
    }
    
    /**
     * Método que tenta registar com o nome e o login.
     */
    public function testRegistrarApenasNomeLoginAction ()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        		'nome' => 'Usuario Teste',
        		'login' => 'd9afbf73c5231e8d68c5d22b7e60e52d',
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('verifysenha', 'email'), array_keys($return->form->getMessages()));
    }
    
    /**
     * Método que tenta registar com o nome e o login.
     */
    public function testRegistrarApenasNomeLoginSenhaAction ()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        		'nome' => 'Usuario Teste',
        		'login' => 'd9afbf73c5231e8d68c5d22b7e60e52d',
        		'senha' => 'teste1234',
        		'verifysenha' => 'teste1234',
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('email'), array_keys($return->form->getMessages()));
    }
    
    /**
     * Método que tenta registar o usuário com tamanhos superiores aos
     * da regra do banco.
     */
    public function testRegistrarTamanhosInvalidosAction ()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        		'nome' => 'Usuario Teste hdfgds mkjfjfjfjf mkjfjfjfjf mkjfjfjfjf mkjfjfjfjf Usuario Teste hdfgds mkjfjfjfjf mkjfjfjfjf mkjfjfjfjf mkjfjfjfjf',
        		'login' => 'd9afbf73c5231e8d68c5d22b7e60e52dausdfuiahsdufihufasdasdasdasdasdasdasdasdasdddasdasdasdahasfhfauisfuaihfuihsuifaufihsfuiahfuaihsf',
        		'senha' => 'teste1234',
        		'verifysenha' => 'teste1234',
                'email' => 'no-reply@captura.measdasdfsdfasfasfsdfasdfjuioasjdfashfapoihsfuioasdofuihasdouhfafiuahfoaiufuiasdofiuasdfuioahsdofuiahifahifhasoif',
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('nome','login','email'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que tenta registar o usuário com tamanhos inferiores aos
     * da regra da aplicação.
     */
    public function testRegistrarTamanhosPequenosAction ()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        		'nome' => 'a',
        		'login' => 'a',
        		'senha' => 't',
        		'verifysenha' => 't',
                'email' => 't',
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('nome','login','verifysenha','email'), array_keys($return->form->getMessages()));
        
    }

    /**
     * Método que tenta registar o usuário com tamanhos superiores aos
     * da regra do banco.
     */
    public function testRegistrarItensInvalidosAction ()
    {
        $this->routeMatch->setParam('action', 'registrar');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        		'nome' => 'Usuario Teste hdfgds',
        		'login' => '_ * #$%()()@%#$!#$%!$#$!5',
        		'senha' => 'teste1234',
        		'verifysenha' => 'teste1234',
                'email' => 'no-reply@captura.me',
                'cpf'  => 'aaa.aaa.aaa-vv'
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login','cpf'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa um visitante executando a ação de deslogar.
     * @expectedException Common\Exception\SemPermissaoException
     */
    public function testVisitanteLogoutAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'logout');
        $this->controller->dispatch($this->request);
    }
    
    /**
     * Método que testa um visitante executando a ação de deslogar.
     */
    public function testUsuarioLogoutAction ()
    {
        $capturador = (object) array( 'id_usuario' => 1, 'tipo' => 'usuario', 'nome' => 'usuario teste','login' => 'teste.login', 'senha' => 'bb52d1f60e462bce604261c389de7eae', 'cpf' => '07228983475', 'email' => 'no-reply@captura.me', 'ativo' => 1,);
        CapturaAuth::getInstance()->setUser($capturador);
        $this->controller->setUser(CapturaAuth::getInstance());
        $this->routeMatch->setParam('action', 'logout');
        $this->controller->dispatch($this->request);
    }
    
    /**
     * Método que testa um visitante executando a ação de deslogar.
     */
    public function testVisitanteValidarAction ()
    {
        CapturaAuth::getInstance()->logout();
        try{
            $this->routeMatch->setParam('action', 'logout');
            $this->controller->dispatch($this->request);
        } catch (\Common\Exception\SemPermissaoException $e){
            $response = $this->controller->getResponse();
        }
        
        // TODO: verificar como se pega a resposta de onde ele leva após logout
    }
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginSemDadosVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(null
        )));
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login','senha'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginComCampoLoginVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        	'login' => 'login.valido',
        )));
        
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('senha'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginComCampoSenhaVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
        	'senha' => '1234234324'
        )));
        
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginComCampoLoginInvalidoVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
            'login' => '_ * #$%()()@%#$!#$%!$#$!5',
        	'senha' => '1234234324'
        )));
        
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginComLoginSenhaTamanhosInferioresVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
            'login' => '1234',
        	'senha' => '12345'
        )));
        
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login','senha'), array_keys($return->form->getMessages()));
        
    }
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginComLoginSenhaTamanhosSuperioresVisitanteAction ()
    {
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
            'login' => '12345123451234512345123451234512345123451234512345',
        	'senha' => '12345123451234512345123451234512345123451234512345123451234512345123451234512345123451234512345123451234512345'
        )));
        
        $return = $this->controller->dispatch($this->request);
        $this->assertSame(array('login','senha'), array_keys($return->form->getMessages()));
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginUsuarioValidoVisitanteAction ()
    {
        $this->testAcessarRegistrarAction();
        
        CapturaAuth::getInstance()->logout();
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters(array(
                'login' => 'd9afbf73c5231e8d68c5d22b7e60e52d',
                'senha' => md5('teste1234'),
        )));
        
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
    }
    
    /**
     * Método que testa a funcionalidade de logar.
     */
    public function testLoginUsuarioInvalidoVisitanteAction ()
    {
    	CapturaAuth::getInstance()->logout();
    	$this->routeMatch->setParam('action', 'login');
    	$this->request->setMethod('POST');
    	$this->request->setPost( new Parameters(array(
    			'login' => 'testeusuarioinexistentenabase',
    			'senha' => md5('naoseiquesenhavoucolocaragora'),
    	)));
    
    	$this->controller->dispatch($this->request);
    	$response = $this->controller->getResponse();
    	$this->assertEquals(200, $response->getStatusCode());
    	
    	// TODO: verificar como se pega as mensagens do flash message
//     	var_dump($this->controller->flashMessenger()->getMessages());
    
    }
    
    
    /**
     * Método que faz as ações finais em cada um dos testes,
     * atualmente é responsável por:
     * 1 - Remover o usuário teste caso ele exista
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    public function tearDown()
    {
        $user = new User();
        $user->exchangeArray(array(
            'nome' => 'Usuario Teste',
            'login' => 'd9afbf73c5231e8d68c5d22b7e60e52d',
            'senha' => md5('teste1234'),
            'verifysenha' => md5('teste1234'),
            'cpf' => '11585425346',
        ));
        $resultado = $this->controller->getModel('User', 'User')->getTable('User','User')->findUsuario($user);
        if($resultado->count()){
            $this->controller->getModel()->getTable()->deleteUsuario($user);
        }
    }
    
}