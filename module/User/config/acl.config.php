<?php
// novidade = gerente
// artigo = escritor
// dica = usuario
return array(
    'roles' => array(
        'visitante' => null, 
        'usuario' => 'visitante',
        'escritor' => 'usuario',
        'gerente' => 'escritor', 
        'root' => 'gerente', 
    ), 
    'resources' => array(
        'ApplicationIndexController',
        'ApplicationCalendarioController',
        'ApplicationUserController',
        'ApplicationCaptchaController',
        'ApplicationTwitterController',
        'ApplicationApoioController',
        'UserUserController',
        'UserConteudoController',
        'UserFacebookController',
        'CapturadaIndexController',
        'CapturadaCapturadaController',
        'CapturadaBatalhaController',
        'CapturadaTagController',
        'CapturadaEventoController',
    ),
    'allow' => array(
        'visitante' => array(
            'ApplicationIndexController' => array(
                'index', 'entre', 'batalhas', 'videos',
                'capturadores', 'sugestoes', 'reunioes','quem-somos','info', 'resolucao',
            ),   
            'ApplicationCalendarioController' => array(
                'index', 
            ),   
            'ApplicationApoioController' => array(
                'adicionar-apoio', 
            ),   
            'CapturadaEventoController' => array(
                'listar-eventos'
            ),
            'CapturadaTagController' => array(
                'listar-tags', 'listar-tag','visualizar-tag', 'buscar-tags', 'get-tag',
            ),   
            'CapturadaIndexController' => array(
                'visualizar-capturada', 'visualizar-capturadas', 'peguei-imagem-capturada', 'nao-peguei-imagem-capturada', 'download-capturada', 
                'capturada-randomica', 'mais-curtidas', 'novas-capturadas', 'get-capturada-escondida', 
            ),   
            'UserUserController' => array(
                'login', 'registrar', 'validar','esqueceu-senha',
            ),   
            'UserFacebookController' => array(
                'facebook-login'
            ),
            'UserConteudoController' => array(
                'index', 'dicas', 'revistas',
            ),   
            'ApplicationCaptchaController' => array(
                'gerar-captcha',
            ),   
            'CapturadaBatalhaController' => array(
                'visualizar-batalha', 'peguei-capturada', 'nao-peguei-capturada', 
            ),
        ),
        'usuario' => array(
            'ApplicationIndexController' => array(
            	'adicionar-evento'
            ),
            'UserFacebookController' => array(
                'vincular-facebook-capturame'
            ),
            'UserUserController' => array(
                'index', 'logout', 'modificar-senha', 'editar-usuario', 
            ),   
            'CapturadaIndexController' => array(
               'editar-capturada', 'adicionar-tag'
            ),
            'CapturadaCapturadaController' => array(
           		'adicionar-capturada'
            ),
            'CapturadaBatalhaController' => array(
                'cadastrar-capturada-batalha', 'selecionar-capturada', 'batalhe', 'nao-batalhe',
            ),
            'CapturadaTagController' => array(
                'add-tag', 'desvincular-tag',
            ),
            'UserConteudoController' => array(
            	'adicionar-dica','deletar-conteudo',
            ),
        ),
        'escritor' => array(
            'UserConteudoController' => array(
            	'adicionar-artigo'
            ),
        ),
        'gerente' => array(
            'UserConteudoController' => array(
            	'adicionar-novidade'
            ),
        ),
        'root' => array(
            'CapturadaBatalhaController' => array(
                'cadastrar-batalha',
            ),
            'UserConteudoController' => array(
            	'adicionar-revista',
            ),
        ),
    ),
    'deny' => array(
        'visitante' => array(
            'UserUserController' => array(
                'index',
            ),   
        ),
    ),
);
?>