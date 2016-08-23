<?php
use Extended\Object\ModuleConfig;
return array(
    'controllers' => array(
        'invokables' => array(
              'Capturada\Controller\Index' => 'Capturada\Controller\IndexController' 
            , 'Capturada\Controller\Batalha' => 'Capturada\Controller\BatalhaController' 
            , 'Capturada\Controller\Tag' => 'Capturada\Controller\TagController'
            , 'Capturada\Controller\TestRoute' => 'Capturada\Controller\TestRouteController'
            , 'Capturada\Controller\Evento' => 'Capturada\Controller\EventoController'
            , 'Capturada\Controller\Notificacao' => 'Capturada\Controller\NotificacaoController'
            , 'Capturada\Controller\Capturada' => 'Capturada\Controller\CapturadaController'
        )
    ), 
    
    // Definição de uma rota
    'router' => array(
        'routes' => array_merge(
            ModuleConfig::getLiteralRoute('listar-capturada', '/listar-capturada', 'capturada', 'Index', 'listar-capturada')

          // OK
          , ModuleConfig::getLiteralRoute('adicionar-capturada', '/adicionar-capturada', 'capturada', 'Capturada', 'adicionar-capturada')
                
          ,  ModuleConfig::getSegmentRoute('visualizar-capturada', '/visualizar-capturada[/:user][/:capturada]', 'capturada', 'Index', 'visualizar-capturada',array('user' => '[0-9]+', 'capturada' => '[0-9]+' ))
          ,  ModuleConfig::getSegmentRoute('editar-capturada', '/editar-capturada[/:user][/:capturada]', 'capturada', 'Index', 'editar-capturada',array('user' => '[0-9]+', 'capturada' => '[0-9]+' ))
          ,  ModuleConfig::getSegmentRoute('capturada-randomica', '/capturada-randomica', 'capturada', 'Index', 'capturada-randomica')
          ,  ModuleConfig::getSegmentRoute('get-capturada-escondida', '/get-capturada-escondida[/:user][/:capturada]', 'capturada', 'Index', 'get-capturada-escondida',array('user' => '[0-9]+', 'capturada' => '[0-9]+' ))
          
                
          ,  ModuleConfig::getSegmentRoute('mais-curtidas', '/mais-curtidas[/:pagina][/:capturada]', 'capturada', 'Index', 'mais-curtidas',array())
          ,  ModuleConfig::getSegmentRoute('novas-capturadas', '/novas-capturadas[/:pagina][/:capturada]', 'capturada', 'Index', 'novas-capturadas',array())
                  
          ,  ModuleConfig::getSegmentRoute('visualizar-capturadas', '/visualizar-capturadas/:user/:pagina[/:capturada]', 'capturada', 'Index', 'visualizar-capturadas',array('user' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('visualizar-batalha', '/visualizar-batalha/:batalha/:pagina[/:capturada]', 'capturada', 'Batalha', 'visualizar-batalha', array('batalha' => '[0-9]+','pagina' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('tag', '/tag/:nome/:pagina[/:capturada]', 'capturada', 'Tag', 'listar-tag',array('pagina' => '[0-9]+'))
                
          // batalhas                
          ,  ModuleConfig::getSegmentRoute('cadastrar-capturada-batalha', '/cadastrar-capturada-batalha[/:pagina][/:capturada]', 'capturada', 'Batalha', 'cadastrar-capturada-batalha', array('pagina' => '[0-9]+'))
                
          ,  ModuleConfig::getSegmentRoute('cadastrar-batalha', '/cadastrar-batalha', 'capturada', 'Batalha', 'cadastrar-batalha',array())
          ,  ModuleConfig::getSegmentRoute('selecionar-capturada', '/selecionar-capturada[/]', 'capturada', 'Batalha', 'selecionar-capturada',array())
          
          ,  ModuleConfig::getSegmentRoute('batalhe', '/batalhe', 'capturada', 'Batalha', 'batalhe',array())
          ,  ModuleConfig::getSegmentRoute('nao-batalhe', '/nao-batalhe', 'capturada', 'Batalha', 'nao-batalhe',array())
                
                
          ,  ModuleConfig::getSegmentRoute('peguei-capturada', '/peguei-capturada[/]', 'capturada', 'Batalha', 'peguei-capturada',array())
          ,  ModuleConfig::getSegmentRoute('nao-peguei-capturada', '/nao-peguei-capturada[/]', 'capturada', 'Batalha', 'nao-peguei-capturada',array())
          ,  ModuleConfig::getSegmentRoute('peguei-imagem-capturada', '/peguei-imagem-capturada[/]', 'capturada', 'index', 'peguei-imagem-capturada',array())
          ,  ModuleConfig::getSegmentRoute('nao-peguei-imagem-capturada', '/nao-peguei-imagem-capturada[/]', 'capturada', 'index', 'nao-peguei-imagem-capturada',array())
          ,  ModuleConfig::getSegmentRoute('download-capturada', '/download-capturada[/]', 'capturada', 'index', 'download-capturada',array())
                
          ,  ModuleConfig::getSegmentRoute('listar-eventos', '/eventos[/][:pagina]', 'capturada', 'Evento', 'listar-eventos',array('pagina' => '[0-9]+'))

          ,  ModuleConfig::getSegmentRoute('adicionar-tag', '/adicionar-tag[/:user][/:capturada]', 'capturada', 'Index', 'adicionar-tag',array('user' => '[0-9]+','capturada' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('listar-tags', '/tags[/][:pagina]', 'capturada', 'Tag', 'listar-tags',array('pagina' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('visualizar-tag', '/visualizar-tag[/:nome][/:pagina]', 'capturada', 'Tag', 'visualizar-tag',array('pagina' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('add-tag', '/add-tag[/:user][/:capturada]', 'capturada', 'Tag', 'add-tag',array('user' => '[0-9]+','capturada' => '[0-9]+'))
          ,  ModuleConfig::getSegmentRoute('get-tag', '/get-tag', 'capturada', 'Tag', 'get-tag',array())
          ,  ModuleConfig::getSegmentRoute('desvincular-tag', '/desvincular-tag', 'capturada', 'Tag', 'desvincular-tag',array())
          ,  ModuleConfig::getSegmentRoute('buscar-tags', '/buscar-tags[/][:pagina]', 'capturada', 'Tag', 'buscar-tags',array('pagina' => '[0-9]+'))
                
                
                
        )
    ),
);