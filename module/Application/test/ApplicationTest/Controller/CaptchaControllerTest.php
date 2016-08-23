<?php

namespace ApplicationTest\Controller;

use Application\Controller\CaptchaController;

use User\Auth\CapturaAuth;

use Zend\Mvc\Application;

use ApplicationTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class CaptchaControllerTest extends PHPUnit_Framework_TestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp ()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new CaptchaController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array(
            'controller' => 'captcha'
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
    public function testGerarCaptchaAction()
    {
        $this->routeMatch->setParam('action', 'gerar-captcha');
        $retorno = $this->controller->onDispatch($this->controller->getEvent());
        $this->assertEquals(200, $retorno->getStatusCode());
    }
    
}