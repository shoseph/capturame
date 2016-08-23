<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;
use Application\View\Helper\FacebookVincularUsuarioHelper;

use Application\View\Helper\FacebookLoginHelper;

use Application\View\Helper\FacebookDadosHelper;

use Application\View\Helper\DestaqueHelper;

use Application\View\Helper\MenuPrincipalHelper;

use Application\View\Helper\MenuBatalhandoFlutuanteHelper;

use Application\View\Helper\MsgSobrepostaHelper;

use Application\View\Helper\LoadCapturadaHelper;

use Application\View\Helper\BuscaHelper;

use Application\View\Helper\MenuRapidoHelper;

use Application\View\Helper\TituloHelper;

use Application\View\Helper\MsgHelper;

use Application\View\Helper\LoginHelper;

use Application\View\Helper\MenuFlutuanteHelper;

use Application\View\Helper\BotaoVoltarHelper;

use Application\View\Helper\PaginadorHelper;

use Application\View\Helper\CurtirHelper;
use Application\View\Helper\NoticiasHelper;
use Application\View\Helper\SliderHelper;
use Application\View\Helper\CamposRequeridosHelper;
use Application\View\Helper\FlashMessengerHelper;
use Application\View\Helper\FotosRandomicasHelper;
use Zend\Mvc\ModuleRouteListener;
use Application\View\Helper\TwitterHelper;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'vendor' => constant('ROOT') . 'vendor/',
                    'Extended' => constant('ROOT') . 'vendor/Extended/',
                    'Facebook' => constant('ROOT') . 'vendor/Facebook/',
                    'Common' => constant('ROOT') . 'Common/',
                    'Library' => constant('ROOT') . 'vendor/Library/',
                    'ZendGData' => constant('ROOT') . 'vendor/google/ZendGData/library/ZendGData',
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
    	return array(
            'factories' => array(
                 'menuFlutuanteHelper' => function($sm) { return new MenuFlutuanteHelper($sm); },
                 'menuPrincipalHelper' => function($sm) { return new MenuPrincipalHelper($sm); },
                 'menuBatalhandoFlutuanteHelper' => function($sm) { return new MenuBatalhandoFlutuanteHelper($sm); },
                 'loginHelper' => function($sm) { return new LoginHelper($sm); },
                 'msgHelper' => function($sm) { return new MsgHelper($sm); },
                 'tituloHelper' => function($sm) { return new TituloHelper($sm); },
                 'camposRequeridos' => function($sm) { return new CamposRequeridosHelper($sm); },
                 'paginadorHelper' => function($sm) { return new PaginadorHelper($sm); },
                 'menuRapidoHelper' => function($sm) { return new MenuRapidoHelper($sm); },
                 'buscaHelper' => function($sm) { return new BuscaHelper($sm); },
                 'loadCapturadaHelper' => function($sm) { return new LoadCapturadaHelper($sm); },
                 'msgSobrepostaHelper' => function($sm) { return new MsgSobrepostaHelper($sm); },
                 'destaqueHelper' => function($sm) { return new DestaqueHelper($sm); },
                 'facebookDadosHelper' => function($sm) { return new FacebookDadosHelper($sm); },
                 'facebookLoginHelper' => function($sm) { return new FacebookLoginHelper($sm); },
                 'facebookVincularUsuarioHelper' => function($sm) { return new FacebookVincularUsuarioHelper($sm); },
                 
//                  'twitterHelper' => function($sm) { return new TwitterHelper(); },
//                  'voltarHelper' => function($sm) { return new BotaoVoltarHelper($sm); },
//                  'noticiasHelper' => function($sm) { return new NoticiasHelper($sm); },
//                  'curtirHelper' => function($sm) { return new CurtirHelper($sm); },
//                  'fotosRandomicasHelper' => function($sm) { return new FotosRandomicasHelper($sm); },
//                  'flashMessenger' => function($sm) { return new FlashMessengerHelper(); },
//                  'slider' => function($sm) { return new SliderHelper($sm); },
             ),
    	);
    }
}
