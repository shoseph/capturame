<?php
namespace Capturada\Model;
use Common\Exception\PontoCadastradoException;

use Capturada\Entity\BatalhaPonto;

use User\Auth\CapturaAuth;

use Capturada\Entity\Capturada;

use Capturada\Entity\BatalhaCapturada;

use Capturada\Entity\Batalha;

use Zend\Form\Form;
use Extended\Model\CapturaModel;

/**
 * Class que contém as regras de negócio de uma batalha.
 */
class BatalhaModel extends CapturaModel
{
    
    /**
     * Método que cadastra uma nova batalha.
     * @param Form $capturada formulário de uma nova batalha 
     * @throws \Common\Exception\CapturadaExistenteException
     */
    public function cadastrarBatalha(Form  $form)
    {
        $batalha = new Batalha();
        $batalha->exchangeArray($form->getData());
        $batalha->dtInicio = implode(!strstr($batalha->dtInicio, '/') ? "/" : "-", array_reverse(explode(!strstr($batalha->dtInicio, '/') ? "-" : "/", $batalha->dtInicio)));
        $batalha->dtFim = implode(!strstr($batalha->dtFim, '/') ? "/" : "-", array_reverse(explode(!strstr($batalha->dtFim, '/') ? "-" : "/", $batalha->dtFim)));
        $this->getTable('batalha')->save($batalha);
    }
    
    /**
     * Método que calcula a quantidade de imagens de um determinado usuário.
     * @param Integer $idUsuario identificador do usuário.
     */
    public function getQuantidadeMaximaEmBatalha($idUsuario)
    {
        $quantidadeEmBatalhas = $this->getTable('batalhaCapturada')->getQuantidadeCapturadasEmBatalha($idUsuario);
        $quantidadeTotalDeFotos = $this->getTable('usuarioCapturada')->getQuantidadeCapturadas($idUsuario);
        return $quantidadeTotalDeFotos - $quantidadeEmBatalhas;
    }
    
    /**
     * Método que calcula a quantidade de imagens capturadas em batalha atual.
     * @param Integer $idUsuario identificador do usuário.
     * @param Integer $idBatalha identificador da batalha.
     */
    public function getQuantidadeCapturadasEmBatalhaAtual($idUsuario, $idBatalha)
    {
        return $this->getTable('batalhaCapturada')->getQuantidadeCapturadasEmBatalhaAtual($idUsuario, $idBatalha);
    }
    
    /**
     * Método que calcula a quantidade de imagens capturadas em batalha atual.
     * @param Integer $idBatalha identificador da batalha.
     */
    public function getQuantidadeTotalCapturadasEmBatalha($idBatalha)
    {
        return $this->getTable('batalhaCapturada')->getQuantidadeTotalCapturadasEmBatalha($idBatalha);
    }
    
    /**
     * Método que calcula a quantidade de imagens capturadas em batalha atual.
     * @param Integer $idBatalha identificador da batalha.
     */
    public function getQuantidadeTotalCapturadasEmBatalhasAbertas()
    {
        return $this->getTable('batalhaCapturada')->getQuantidadeTotalCapturadasEmBatalhasAbertas();
    }
    
    /**
     * Método que retorna qual é a batalha atual.
     * @return \Capturada\Entity\Batalha Objeto de uma batalha.
     */
    public function getBatalhasAtuais()
    {
        $rs = $this->getTable('batalha')->getBatalhasAtuais();
        $batalhas = array();
        if($rs->count()){
            
            foreach($rs as $batalhaAtual){
                $batalha = new Batalha();
                $batalha->exchangeArray($batalhaAtual);
                if(CapturaAuth::getInstance()->getUser()){
                    $batalha->suasCapturadasEmBatalha = $quantidadeAtual = $this->getQuantidadeCapturadasEmBatalhaAtual(CapturaAuth::getInstance()->getUser()->id_usuario , $batalha->id_batalha);
                    $batalha->enviarFoto =  $quantidadeAtual >= $batalha->quantidade ? false : true;
                    $batalha->participando =  $quantidadeAtual ? true : false;
                } else {
                    $batalha->enviarFoto = false;
                    $batalha->participando = -1;
                    $batalha->suasCapturadasEmBatalha = 0;
                }
                
                $batalha->quantitadeTotalDeImagens = $this->getQuantidadeTotalCapturadasEmBatalha($batalha->id_batalha);
                $batalha->previewImagens = $this->getModel('batalhaCapturada','capturada')->getImagensBatalha($batalha->id_batalha,1, 3);
                $batalhas[] = $batalha;
            }
            return $batalhas;
        }
        
        return null; 
                    
    }
    /**
     * Método que seta as batalhas no arquivo javascript
     */    
    public function setObjBatalhasNoJavascript()
    {
        $batalhas = $this->getBatalhasAtuais();
        $script = array();
        foreach($batalhas as $batalha){
            $script[] =  "{ 'id_batalha' : '{$batalha->id_batalha}', 'titulo' : '{$batalha->titulo}', 'totalBatalha' : '{$batalha->quantidade}' ,'capturadasEnviadas' : '{$batalha->suasCapturadasEmBatalha}' }";
        }
        $script = 'batalhas = [' . implode(',', $script) . '];';
        $this->getServiceManager()->get('viewhelpermanager')->get('headScript')->appendScript($script);
    }
    
    /**
     * Método que retorna qual a batalha 
     * @return \Capturada\Entity\Batalha Objeto de uma batalha.
     */
    public function getBatalha($batalha)
    {
        $rs = $this->getTable('batalha')->getBatalha($batalha);
        $batalha = new Batalha();
        $batalha->exchangeArray($rs);
        return $batalha;
    }
    
    /**
     * Método que cadastra uma capturada em uma batalha.
     * @param Integer $capturada id da imagem capturada.
     * @param Integer $batalha id da batalha atual.
     * @param Integer $usuario id do usuário logado.
     */
    public function cadastrarCapturadaEmBatalha($capturada, $batalha, $usuario)
    {
        $batalhaCapturada = new BatalhaCapturada();
        $batalhaCapturada->exchangeArray(array(
           'id_capturada' => $capturada, 
           'id_batalha' => $batalha,
           'id_usuario' => $usuario,
           'id_capturada_anterior' => $this->getTable('batalhaCapturada')->getUltimaCapturada($batalha)
        ));
        $this->getTable('batalhaCapturada')->save($batalhaCapturada);
    }
    
    /**
     * Método que retorna as fotos que não foram selecionadas anteriormente em uma
     * batalha.
     * @param Integer $idUsuario Identificador de um usuário.
     * @param Integer $pagina a página em questão
     * @param Integer $limit quantos registros por vez.
     */
    public function getCapturadasNaoCadastradas($idUsuario, $pagina, $limit = 25)
    {
    	$offset = ($pagina * $limit) - $limit;
    	$capturadas =  $this->getTable('batalhaCapturada')->getCapturadasNaoCadastradas($idUsuario, $limit, $offset);
    	$retorno = array();
    	foreach($capturadas as $capturada){
    		$cap = Capturada::getNewInstance()->fillInArray($capturada);
    		$cap->user = $idUsuario;
    		$cap->tags = $this->getModel('index', 'capturada')->getTags($cap->id_capturada);
    		$retorno[] = $cap;
    	}
    	return $retorno;
    }
    
    /**
     * Método que retorna se o usuário está participando de uma batalha.
     * @param Integer $idUsuario identificador de um usuário.
     * @return boolean
     */
    public function getParticipacaoBatalha($idUsuario)
    {
        $retorno = $this->getTable('batalhaCapturada')->getQuantidadeCapturadasEmBatalhaAtual($idUsuario);
        return $retorno > 0 ? true : false;
    }
    
    /**
     * Método que retorna as capturadas da pagina.
     * @param Integer $pagina pagina atual.
     * @param Integer $limit quantidade de campos a serem exibidos por vez
     */
    public function getCapturadaNaBatalha($batalha, $pagina, $limit = 1)
    {
        $offset = ($pagina * $limit) - $limit;
        $capturadas =  $this->getTable('batalhaCapturada')->getCapturadasNaBatalha($batalha, $limit, $offset);
    	$retorno = array();
    	foreach($capturadas as $capturada)
    	{
    	    $batalhaCapturada = BatalhaCapturada::getInstance()->fillInArray($capturada);
    		$batalhaCapturada->setPegou($this->getTable('batalhaPonto')->getPegouCapturada($batalhaCapturada->id_batalha_capturada));
    		$batalhaCapturada->setNaoPegou($this->getTable('batalhaPonto')->getNaoPegouCapturada($batalhaCapturada->id_batalha_capturada));
    		$cap = Capturada::getInstance()->fillInArray($capturada);
    		$cap->user = $capturada->id_usuario;
    		$cap->tags = $this->getModel('index', 'capturada')->getTags($capturada->id_capturada);
    	    $batalhaCapturada->setCapturada($cap);
    	    $bat = Batalha::getInstance()->fillInArray($capturada);
    	    $batalhaCapturada->setBatalha($bat);
    		$retorno[] = $batalhaCapturada;
    	}
    	return $retorno;
    }

    
    /**
     * Método que cadastra um peguei de um visitante no site.
     * @param Integer $batalha identificador da imagem na batalha.
     */
    public function cadastrarPegueiVisitante($batalha)
    {
        // inserir apenas na tabela cp_batalha_ponto
        $batalhaPonto = new BatalhaPonto();
        $batalhaPonto->exchangeArray(array('id_batalha_capturada' => $batalha, 'id_usuario_acao' => -1, 'pegou' => 1));
        $this->getTable('batalhaPonto')->save($batalhaPonto);
        return constant('VALOR_PONTOS_VISITANTE');
    }
    
    /**
     * Método que cadastra um não peguei de um visitante no site.
     * @param Integer $batalha identificador da imagem na batalha.
     */
    public function cadastrarNaoPegueiVisitante($batalha)
    {
        // inserir apenas na tabela cp_batalha_ponto
        $batalhaPonto = new BatalhaPonto();
        $batalhaPonto->exchangeArray(array('id_batalha_capturada' => $batalha, 'id_usuario_acao' => -1, 'naoPegou' => 1));
        $this->getTable('batalhaPonto')->save($batalhaPonto);
        return constant('VALOR_PONTOS_VISITANTE');
    }
    
    /**
     * Método que retorna qual é a pontuação de um determinado tipo
     * de usuário no captura.me
     * @param string $tipo identificador de tipo de usuário
     * @return Integer Quantidade de pontos que vale um usuário.
     */
    public function getPontosPeloTipoUsuario($tipo)
    {
        $pontos = 0;
        switch($tipo) {
        	case 'usuario': $pontos = constant('VALOR_PONTOS_USUARIO'); break;
        	case 'escritor': $pontos = constant('VALOR_PONTOS_ESCRITOR'); break;
        	case 'gerente': $pontos = constant('VALOR_PONTOS_GERENTE'); break;
        	case 'root': $pontos = constant('VALOR_PONTOS_ROOT'); break;
        	case 'usuario-bronze': $pontos = constant('VALOR_PONTOS_USUARIO_BRONZE'); break;
        	case 'usuario-prata': $pontos = constant('VALOR_PONTOS_USUARIO_PRATA'); break;
        	case 'usuario-ouro': $pontos = constant('VALOR_PONTOS_USUARIO_GOLD'); break;
        	default: $pontos = constant('VALOR_PONTOS_VISITANTE');
        }
        return $pontos;
    }

    /**
     * Método que retorna se o usuário já votou na foto.
     * @param Integer $batalha Identificador da imagem em batalha.
     * @param Usuario $usuario Objeto de tipo usuário autenticado.
     * @return Ambigous <boolean, mixed>
     */
    public function getBatalhaPonto($batalha, $usuario)
    {
        $batalhaPonto = new BatalhaPonto();
        $batalhaPonto->exchangeArray(array('id_batalha_capturada' => $batalha, 'id_usuario_acao' => $usuario->id_usuario));
        $retorno = $this->getTable('batalhaPonto')->getBatalhaPonto($batalhaPonto);
        $retorno = $retorno->count() ? $retorno->current() : false;
        return $retorno;
    }
    
    /**
     * Método que acrescenta um ponto ao peguei
     * @param unknown_type $batalha
     * @param unknown_type $usuario
     * @return boolean
     */
    public function cadastrarPegueiUsuario($batalha , $usuario)
    {
        $pontosUsuario = $this->getPontosPeloTipoUsuario($usuario->tipo);
        $batalhaPonto = new BatalhaPonto();
        $batalhaPonto->exchangeArray(array('id_batalha_capturada' => $batalha, 'id_usuario_acao' => $usuario->id_usuario));
        $retorno = $this->getTable('batalhaPonto')->getBatalhaPontoDoDia($batalhaPonto);

        // verificar se já existe um voto do dia de hoje
        if($retorno->count()){
           throw new PontoCadastradoException(); 
        }
        
        // inserir uma novo ponto
        $batalhaPonto->pegou = $pontosUsuario;
        $this->getTable('batalhaPonto')->save($batalhaPonto);
        
        return $pontosUsuario;
    }
    
    /**
     * Método que acrescenta um ponto ao não peguei
     * @param unknown_type $batalha
     * @param unknown_type $usuario
     * @return boolean
     */
    public function cadastrarNaoPegueiUsuario($batalha , $usuario)
    {
        $pontosUsuario = $this->getPontosPeloTipoUsuario($usuario->tipo);
        $batalhaPonto = new BatalhaPonto();
        $batalhaPonto->exchangeArray(array('id_batalha_capturada' => $batalha, 'id_usuario_acao' => $usuario->id_usuario));
        $retorno = $this->getTable('batalhaPonto')->getBatalhaPontoDoDia($batalhaPonto);
        
        // verificar se já existe um voto do dia de hoje
        if($retorno->count()){
        	throw new PontoCadastradoException();
        }
        
        // inserir uma novo ponto
        $batalhaPonto->naoPegou = $pontosUsuario;
        
        $this->getTable('batalhaPonto')->save($batalhaPonto);
        
        return $pontosUsuario;
    }
    
    /**
     * Método que retorna a quantidade de batalhas abertas
     */
    public function getQuantidadeBatalhasAbertas()
    {
    	return $this->getTable('Batalha', 'capturada')->getQuantidadeBatalhasAbertas();
    }
}
