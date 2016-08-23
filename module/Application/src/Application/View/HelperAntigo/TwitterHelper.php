<?php
namespace Application\View\Helper;
use Zend\Loader\PluginClassLoader;
use Zend\View\Helper\AbstractHelper;
use Zend\Feed\Reader\Reader;
// use Zend\XmlRpc\Generator\DomDocument;
class TwitterHelper extends AbstractHelper
{
    private $simpleXml;
    private $quantidade = 3;
    private $cores = array('laranja','laranjaClaro','azul','vermelho','magenta', 'amarelo','roxo','verde','verdeClaro');

	public function __construct()
	{
	    // carregando o xml
	    $this->simpleXml = simplexml_load_file('http://twitter.com/statuses/user_timeline/mecaptura.rss');
	}
	
	private function tratamentoTweet($tweet){
	    
	    // Separando os : que vem com o nome do usuário
	    $tweet = substr($tweet, stripos($tweet, ':') + 1);
	    
	    // Adicionando link a uma url
	    $tweet = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $tweet);
	    
	    // Retirando os espaços em branco e adicionando referência de link a um usuário do twitter
	    return trim(preg_replace("/@([0-9a-zA-Z]+)/", "<a href=\"http://twitter.com/$1\">@$1</a>", $tweet));
	}
	
	private function getUser($tweet){
	    
	    if(!$tweet)
	        return 'mecaptura';
	    
	}
	
	private function modelaXmlElement($tweet){
	    
	    $retorno = new \stdClass();
	    PluginClassLoader::addStaticMap(array(
	    'url' => 'Zend\Debug',
	    ));
	    if($tweet == null)
	        return null;

	    $retorno->data = date('d-m',strtotime(strip_tags($tweet->pubDate->asXML())));
	    $retorno->hora = date('y:m',strtotime(strip_tags($tweet->pubDate->asXML())));
  	    $retorno->tweet = $this->tratamentoTweet(strip_tags($tweet->description->asXML()));
  	    $retorno->cor = $this->cores[array_rand($this->cores,1)];
  	    $retorno->user = $this->getUser(null);
 	    
 	    return $retorno;
	}

	public function __invoke()
	{

	    $retorno = array();
	    for($x=0; $x < $this->quantidade; $x++){
	        $retorno[] = $this->modelaXmlElement($this->simpleXml->channel->item[$x]);
	    }
	    
	    return $retorno;
		
	}
}