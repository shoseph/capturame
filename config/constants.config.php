<?php

// defined the enviroment
define('APPLICATION_ENV', 'deve');

// defined the root directory
define('ROOT', __DIR__ . '/../' );

// defined the data directory
define('DATA', constant('ROOT') . 'data/' );

// defined the library directory
// define('LIBRARY', constant('ROOT') . 'vendor/Library/' );

// defined the captcha directory
define('CAPTCHA', constant('DATA') . 'captcha/' );

// defined the public directory
define('PUBLIC', constant('ROOT') . 'public/');

// defined the javascript directory
define('JS', constant('ROOT') . 'public/js/');

// defined the folder of the application
define('APPLICATION', constant('ROOT') . 'module/Application/');

// defined the translate folder of the application
define('LANGUAGE', constant('APPLICATION') . 'language/');

// defined the folder of the view
define('VIEW', constant('PUBLIC') . 'view/');

// Email que não necessita de resposta
define('NO-REPLY', 'no-reply@captura.me');
define('NO-REPLY-NAME', 'captura.me');

// Quantidade de fotos  por cada batalha
define('QUANTIDADE-FOTOS-POR-BATALHA',4);

// valores relacionados a curtir de uma imagem
define('VALOR_CURTIR_IMAGEM',1);

// Constantes de quantidade de visualização de imagens por página
define('PAGINACAO_NORMAL',25);
define('PAGINACAO_EXTENDIDA',50);
define('PAGINACAO_GIGANTE',100);

// valores relacionados ao pontos
define('VALOR_PONTOS_VISITANTE',1);
define('VALOR_PONTOS_USUARIO',100);
define('VALOR_PONTOS_ESCRITOR',150);
define('VALOR_PONTOS_GERENTE',200);
define('VALOR_PONTOS_ROOT',250);

define('VALOR_PONTOS_USUARIO_BRONZE',200);
define('VALOR_PONTOS_USUARIO_PRATA',400);
define('VALOR_PONTOS_USUARIO_GOLD',1000);