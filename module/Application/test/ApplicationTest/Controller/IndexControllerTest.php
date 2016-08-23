<?php

namespace ApplicationTest\Controller;

use Zend\Stdlib\Parameters;

use User\Auth\CapturaAuth;

use Zend\Mvc\Application;

use ApplicationTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class IndexControllerTest extends PHPUnit_Framework_TestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp ()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new IndexController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array(
            'controller' => 'index'
        ));
        
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
     * Método que testa o acesso a funcionalidade do index.
     */
    public function testAcessarIndexAction ()
    {
        $this->routeMatch->setParam('action', 'index');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    
    /**
     * Método que testa o acesso a funcionalidade do Quem Somos.
     */
    public function testQuemSomosAction ()
    {
        $this->routeMatch->setParam('action', 'quem-somos');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    
    /**
     * Método que testa o acesso a funcionalidade do Batalhas. 
     */
    public function testBatalhasAction ()
    {
        $this->routeMatch->setParam('action', 'batalhas');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    
    /**
     * Método que testa o acesso a funcionalidade do Usuários 
     */
    public function testUsuariosAction ()
    {
        $this->routeMatch->setParam('action', 'usuarios');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    /**
     * Método que testa o acesso a funcionalidade sugestão de evento
     */
    public function testsugestoesEventoVisitanteAction ()
    {
        $this->routeMatch->setParam('action', 'sugestoes');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    
    /**
     * Método que testa o acesso a functionalidade sugestão de evento de um usuário.
     */
    public function testsugestoesEventoUsuarioAction ()
    {
        $capturador = (object) array( 'id_usuario' => 1, 'tipo' => 'usuario', 'nome' => 'usuario teste','login' => 'teste.login', 'senha' => 'bb52d1f60e462bce604261c389de7eae', 'cpf' => '07228983475', 'email' => 'no-reply@captura.me', 'ativo' => 1,);
        CapturaAuth::getInstance()->setUser($capturador);
        $this->controller->setUser(CapturaAuth::getInstance());
        
        $this->routeMatch->setParam('action', 'sugestoes');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $retorno);
    }
    
    /**
     * Conjunto de dados a serem testados pela testsugestoesEvento
     */
    public function dateProvidersugestoes()
    {
        return array(
                         // post                                                                             // response
             array(array('evento' => 'e', 'email' => 'e', 'descricao' => 'e', 'data' => 'D', 'hora' => 'h'), array('evento','email','data','hora','descricao')),
             array(array('evento' => 'evento', ), array('email','data','hora','descricao')),
             array(array('email' => 'email@email.com', ), array('evento','data','hora','descricao')),
             array(array('data' => '12/10/2012', ), array('evento','email','hora','descricao')),
             array(array('hora' => '12:10', ), array('evento','email','data','descricao')),
             array(array('descricao' => 'evento legal', ), array('evento','email','data','hora',)),
             array(array('evento' => 'evento bacana', 'email' => 'strubloid@gmail.com', 'descricao' => 'evento no riachinho', 'data' => '10/10/2013', 'hora' => '-10:00'), array('hora')),
             array(array('evento' => 'evento bacana', 'email' => 'strubloid@gmail.com', 'descricao' => 'porracaraleobuceta!', 'data' => 'asdasda', 'hora' => '-1000'), array('data','hora','descricao')),
             array(array('evento' => 'evento bacana', 'email' => 'strubloidgmail.com', 'descricao' => 'evento no riachinho', 'data' => '10/10/2013', 'hora' => 'aasdasdas'), array('email', 'hora')),
        );
    }
    
    /**
     * Método que testa o acesso a functionalidade sugestão de evento de um usuário.
     * @dataProvider dateProvidersugestoes
     */
    public function testsugestoesEventoUsuarioDadosPequenosAction ($post, $resp)
    {
        $capturador = (object) array( 'id_usuario' => 1, 'tipo' => 'usuario', 'nome' => 'usuario teste','login' => 'teste.login', 'senha' => 'bb52d1f60e462bce604261c389de7eae', 'cpf' => '07228983475', 'email' => 'no-reply@captura.me', 'ativo' => 1,);
        CapturaAuth::getInstance()->setUser($capturador);
        $this->controller->setUser(CapturaAuth::getInstance());
        
        $this->request->setMethod('POST');
        $this->request->setPost( new Parameters($post));
        
        $this->routeMatch->setParam('action', 'sugestoes');
        $return = $this->controller->dispatch($this->request);
        $this->assertSame($resp, array_keys($return->form->getMessages()));
        
    }
    
    
}