<?php

namespace Extended\Object;
use Facebook\src\FacebookApiException;
use Facebook\src\Facebook;
use User\Entity\Conteudo;

class FacebookNoticias 
{
    protected $_appid;
    protected $_secret;
    protected $_pageid;
    protected $_token;
    protected static $_instance = null;
    
        
    /**
     * Método que preenche os itens referentes a classe.
     */
    private function setItens()
    {
        \Captura::getEnvironment() == 'prod' ? $this->setFacebookProd() : $this->setFacebookTest();
    }

    /**
     * Método que seta os parâmetros para o ambiente de teste.
     */
    private function setFacebookTest()
    {
        $this->_appid  = '519797261409808';
        $this->_secret = '5a0b0080d23a2e3b579af31ba3a1a16e';
        $this->_pageid = 'joserafaelmendes';
        $this->_token  = 'BAAHYwLsfHhABAGb0iDprYevJisOOTW6HAkWU21Choyt7sIZCoc8gU8f1tArAbI4TIqhDfAeriZBCCs8mukBZAbAqq71CG7XYPoZAy5LIjqsFFx3Dvnem';
    }
    
    /**
     * Método que seta os parâmetros para o ambiente de produção.
     */
    private function setFacebookProd()
    {
        $this->_appid  = '487250587978311';
        $this->_secret = '43f0b941fd5d6b53f392918dda9462ef';
        $this->_pageid = 'capturame';
        $this->_token  = 'BAAG7Jt42ikcBAM8d4HUqXTBwXWk344VwtkEn5Tw4juZBPMMOhkY3CTQMpnuF4J3HCr3uaZAYeT3KnHEdjPM9KcfbQWOnkoLLlkucRUGYvCTFGriYH2';
    }
    
    // Um construtor privado
    private function __construct()
    {  
        $this->setItens();
    }
    
    // O método fabrica
    public static function factory()
    {
        $c = __CLASS__;
        self::$_instance = new $c();
    	return self::$_instance;
    }
    
    /**
     * Método que envia o conteudo para a página do facebook do Captura.Me
     * @param String $tipo tipo de conteúdo
     * @param String $titulo titulo do conteúdo
     * @param String $conteudo Pedaço do conteúdo
     * @param String $link url do conteúdo
     * @param String $foto url para uma imagem
     */
    public function sendConteudo($tipo, $titulo, $conteudo, $link = null, $foto = null)
    {
        $dominio = $_SERVER['SERVER_NAME'];
        $facebook = new Facebook(array('appId'  => $this->_appid,'secret' => $this->_secret));
        $page = $this->_pageid . '/links';
        $tipo = substr($tipo, -1) == 'o' ? 'Ultimo ' . ucfirst($tipo) : 'Ultima ' . ucfirst($tipo);
        $conteudo = html_entity_decode($conteudo, ENT_COMPAT, "UTF-8");
        $conteudo = "{$tipo} no Captura.Me! \n\n{$conteudo}";
        $args = array(
    	    'access_token' => $this->_token,
    	    'message'      => $conteudo,
    	    'link'         => 'http://facebook.com/capturame',
    	    'name'         => $titulo,
    	);
        !$foto?: $args['picture'] = $foto;
    	$post_id = $facebook->api($page, 'POST',$args);
    }
    
    /**
     * Método que envia uma foto para a página do facebook do Captura
     * @param Capturada $capturada Objeto de uma capturada.
     */
    public function sendImage($capturada)
    {
        $dominio = $_SERVER['SERVER_NAME'];
        $facebook = new Facebook(array('appId'  => $this->_appid,'secret' => $this->_secret));
        $page = $this->_pageid . '/links';
        
        $args = array(
            'picture'     => "http://{$dominio}/capturadas/{$capturada->getUser()}/med/med_{$capturada->getArquivo()}.{$capturada->getType()}",
            'link'        => "http://{$dominio}/visualizar-capturada/{$capturada->getUser()}/{$capturada->getId()}",
            'name'        => $capturada->getNome(),
            'description' =>  $capturada->getDescricao() ? $capturada->getDescricao() : '',
    	    'access_token' => $this->_token,
    	    'message'      => "Capturada da Hora: {$capturada->getNome()}",
    	);
    	$post_id = $facebook->api($page, 'POST',$args);
    }
    

}
