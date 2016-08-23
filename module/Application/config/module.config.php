<?php
use Extended\Object\ModuleConfig;
return array(
    'router' => array(
        'routes' => array_merge(
                ModuleConfig::getLiteralRoute('home', '/', 'application', 'Index', 'index',1)
              , ModuleConfig::getSegmentRoute('error', '/error[/:error]', 'application', 'Index', 'index', array())
              , ModuleConfig::getSegmentRoute('reunioes', '/reunioes[/]', 'application', 'Index', 'reunioes')
              , ModuleConfig::getSegmentRoute('resolucao', '/resolucao[/]', 'application', 'Index', 'resolucao')
              , ModuleConfig::getSegmentRoute('info', '/info[/]', 'application', 'Index', 'info')
              , ModuleConfig::getSegmentRoute('quem-somos', '/quem-somos[/]', 'application', 'Index', 'quem-somos')
              , ModuleConfig::getSegmentRoute('capturadores', '/capturadores[/]', 'application', 'Index', 'capturadores')
              , ModuleConfig::getSegmentRoute('batalhas', '/batalhas[/]', 'application', 'Index', 'batalhas')
              , ModuleConfig::getSegmentRoute('sugestoes', '/sugestoes[/]', 'application', 'Index', 'sugestoes')
              , ModuleConfig::getSegmentRoute('adicionar-evento', '/adicionar-evento[/]', 'application', 'Index', 'adicionar-evento')
              , ModuleConfig::getSegmentRoute('gerar-captcha', '/gerar-captcha[/:id]', 'application', 'Captcha', 'gerar-captcha', array('id' => '[\.a-zA-Z0-9_-]*'))
              , ModuleConfig::getSegmentRoute('calendario', '/calendario[/]', 'application', 'calendario', 'index')
              , ModuleConfig::getSegmentRoute('videos', '/videos[/:pagina]', 'application', 'Index', 'videos',array())
              , ModuleConfig::getSegmentRoute('adicionar-apoio', '/adicionar-apoio[/]', 'application', 'apoio', 'adicionar-apoio')
//               , ModuleConfig::getDefaultRoute()
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Twitter' => 'Application\Controller\TwitterController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Captcha' => 'Application\Controller\CaptchaController',
            'Application\Controller\Calendario' => 'Application\Controller\CalendarioController',
            'Application\Controller\Apoio' => 'Application\Controller\ApoioController',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => constant('LANGUAGE'),
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_path_stack' => array(
            constant('VIEW'),
        ),
        'template_map' => array(
            'layout/layout'           => constant('VIEW') . 'layout/layout.phtml',
            'application/index/index' => constant('VIEW') . 'application/index/index.phtml',
            'error/404'               => constant('VIEW') . 'error/404.phtml',
            'error/index'             => constant('VIEW') . 'error/index.phtml',
        ),
        'strategies' => array(
        	'ViewJsonStrategy',
        ),
    ),
    
);
