<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonCapturada for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Capturada;

use Capturada\View\Helper\CapturadaEscondidaHelper;

use Capturada\View\Helper\AdicionarTagHelper;

use Capturada\View\Helper\TagsHelper;
use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function onBootstrap($e)
    {
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
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
    	return array(
            'factories' => array(
                 'tagsHelper' => function($sm) { return new TagsHelper($sm); },
                 'adicionarTagHelper' => function($sm) { return new AdicionarTagHelper($sm); },
                 'capturadaEscondidaHelper' => function($sm) { return new CapturadaEscondidaHelper($sm); },
             ),
    	);
    }
    
}
