<?php
use Extended\Object\ModuleConfig;
return array(
    'controllers' => array(
        'invokables' => array(
      		'User\Controller\User' => 'User\Controller\UserController',
      		'User\Controller\Conteudo' => 'User\Controller\ConteudoController',
      		'User\Controller\Facebook' => 'User\Controller\FacebookController',
      	)
    ),
    'router' => array(
        'routes' => array_merge(
            ModuleConfig::getLiteralRoute('registrar', '/registrar', 'user', 'User', 'registrar')
         ,  ModuleConfig::getLiteralRoute('editar-usuario', '/editar-usuario', 'user', 'User', 'editar-usuario')
         ,  ModuleConfig::getLiteralRoute('login', '/login', 'user', 'User', 'login')
         ,  ModuleConfig::getLiteralRoute('logout', '/logout', 'user', 'User', 'logout')
         ,  ModuleConfig::getSegmentRoute('validar', '/validar[/:hash]', 'user', 'User', 'validar',array('hash' => '[0-9\w=]+'))
         ,  ModuleConfig::getSegmentRoute('user-index', '/user-index[/:user]', 'user', 'User', 'index',array('user' => '[0-9]+'))
         ,  ModuleConfig::getSegmentRoute('esqueceu-senha', '/esqueceu-senha', 'user', 'User', 'esqueceu-senha', array())
         ,  ModuleConfig::getSegmentRoute('modificar-senha', '/modificar-senha', 'user', 'User', 'modificar-senha', array())
         
//          ,  ModuleConfig::getSegmentRoute('novidades', '/novidades', 'user', 'Conteudo', 'index', array())
         ,  ModuleConfig::getSegmentRoute('dicas', '/dicas', 'user', 'Conteudo', 'dicas', array())
                
         ,  ModuleConfig::getSegmentRoute('adicionar-novidade', '/adicionar-novidade', 'user', 'Conteudo', 'adicionar-novidade', array())
         ,  ModuleConfig::getSegmentRoute('adicionar-dica', '/adicionar-dica', 'user', 'Conteudo', 'adicionar-dica', array())
         ,  ModuleConfig::getSegmentRoute('adicionar-artigo', '/adicionar-artigo', 'user', 'Conteudo', 'adicionar-artigo', array())
         ,  ModuleConfig::getSegmentRoute('deletar-conteudo', '/deletar-conteudo[/:user][/:conteudo]', 'user', 'Conteudo', 'deletar-conteudo', array('user' => '[0-9]+', 'conteudo' => '[0-9]+'))
                
                
         ,  ModuleConfig::getSegmentRoute('adicionar-revista', '/adicionar-revista', 'user', 'Conteudo', 'adicionar-revista', array())
         ,  ModuleConfig::getSegmentRoute('revistas', '/revistas', 'user', 'Conteudo', 'revistas', array())
                
         ,  ModuleConfig::getSegmentRoute('facebook-login', '/facebook-login[/:publicar]', 'User', 'Facebook', 'facebook-login', array('publicar' => '[0-1]'))
         ,  ModuleConfig::getSegmentRoute('vincular-facebook-capturame', '/vincular-facebook-capturame', 'User', 'Facebook', 'vincular-facebook-capturame', array())
        )
    ),
 
);
