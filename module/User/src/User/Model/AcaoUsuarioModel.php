<?php
namespace User\Model;
use User\Entity\AcaoUsuario;

use User\Entity\Acao;

use Common\Exception\ConteudoInexistenteException;

use Common\Exception\UsuarioDiferenteException;

use User\Auth\CapturaAuth;

use User\Entity\TipoConteudo;

use User\Entity\Conteudo;

use Common\Exception\CpfExistenteException;

use Common\Exception\LoginExistenteException;

use Common\Exception\UsuarioInvalidoException;

use User\Entity\User;
use Zend\Form\Form;
use Zend\Mail;
use Extended\Model\CapturaModel;
use Common\Exception\UsuarioInativoException;

class AcaoUsuarioModel extends CapturaModel{
    
    
    /**
     * Método que adiciona um ponto a um usuário
     * @param Integer $idUsuario identificador do usuário
     * @param Acao $acao Objeto do tipo ação
     */
    private function adicionarPonto($idUsuario, $acao)
    {
        $ano = date('Y');
        $mes = date('m');

        // procurar a classificação
        $classificacao = $this->getModel('classificacaoUsuario','user')->getUltimaClassificacaoUsuario($idUsuario);
        
        // procurar a reputacao
        $reputacao = $this->getModel('reputacaoUsuario','user')->getUltimaReputacaoUsuario($idUsuario);

        // inserir o ação usuario
        $idAcaoUsuario = $this->adicionarAcaoUsuario($idUsuario, $acao->id_acao, $classificacao->id_classificacao, $ano, $mes);
        
        // inserir o ponto permanente
        $somatorioPontosPermanentes = $this->getModel('PontoPermanente','user')->adicionarPontoPermanente($idUsuario, $acao, $classificacao->classificacao, $reputacao->reputacao, $ano, $mes);

        // inserir o ponto mensal
        $somatorioPontosMensais = $this->getModel('PontoMensal','user')->adicionarPontoMensal($idUsuario, $acao, $classificacao->classificacao);
        
        // atualizar classificação usuário
        $this->getModel('classificacaoUsuario','user')->atualizarClassificacaoUsuario($idUsuario, $classificacao, $somatorioPontosMensais, $ano, $mes);
        
        // atualizar classificação permanente
        $this->getModel('reputacaoUsuario','user')->atualizarReputacaoUsuario($reputacao, $somatorioPontosPermanentes);
        
    }
    
    /**
     * Método que executa a ação de pontuar uma dica
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoDica($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('enviar dica');
        
        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que executa a ação de pontuar um artigo
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoArtigo($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('enviar artigo');
        
        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que executa a ação de pontuar uma curtida
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoCurtir($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('curtir imagem');
        
        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que executa a ação de pontuar uma imagem capturada
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoEnviarCapturada($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('enviar capturada');
        
        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que executa a ação de pontuar uma escolha de uma imagem para uma batalha.
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoEscolherBatalha($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('escolher batalha');

        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que executa a ação de pontuar a informação de um evento.
     * @param Integer $idUsuario Identificador de usuário
     */
    public function pontoEnviarEvento($idUsuario)
    {
        // capturar a ação
        $acao = $this->getModel('acao','user')->getAcao('informar evento');

        // chamar o método de adicionar novo ponto
        $this->adicionarPonto($idUsuario, $acao);
    }
    
    /**
     * Método que adiciona uma ação de um determinado usuário
     * @param Integer $idUsuario identificador do usuário
     * @param Integer $idAcao indentificador da ação
     * @param Integer $idClassificacao identificador da classificação
     * @param Integer $ano ano em que ocorre a ação
     * @param Integer $mes mes em que ocorre a ação
     */
    private function adicionarAcaoUsuario($idUsuario, $idAcao, $idClassificacao, $ano, $mes)
    {
        $acaoUsuario = AcaoUsuario::getNewInstance()->fillinArray(array('id_usuario' => $idUsuario, 'id_acao' => $idAcao, 'id_classificacao' => $idClassificacao, 'mes' => $mes, 'ano' => $ano));
        return !$acaoUsuario ? null : $this->getTable('acaoUsuario','user')->save($acaoUsuario);
    } 
    
    /**
     * Método que retorna a quantidade de imagens enviadas no mês.
     */
    public function getQuantidadeEnviadasMes()
    {
        return $this->getTable('AcaoUsuario', 'user')->getQuantidadeMensalAcao(array(3));
    }
    
    /**
     * Método que retorna a quantidade de novidades no mês.
     */
    public function getQuantidadeNovidadesMes()
    {
        return $this->getTable('AcaoUsuario', 'user')->getQuantidadeMensalAcao(array(5,6));
    }
     
    /**
     * Método que retorna a quantidade de imagens mais curtirdas no mês.
     */
    public function getQuantidadeMaisCurtidasMes()
    {
        return $this->getTable('AcaoUsuario', 'user')->getQuantidadeMensalAcao(array(2));
    }
    
    public function getNovidades($pagina, $limit = 25)
    {
        
//     	$offset = ($pagina * $limit) - $limit;
//     	$capturadas =  $this->getTable('AcaoUsuario','User')->getNovidades($limit, $offset);

//     	dump($capturadas, 1);
//     	$retorno = array();
//     	foreach($capturadas as $capturada){
// //     		$cap = Capturada::getNewInstance()->fillInArray($capturada);
//     		$cap->user = $capturada->id_usuario;
//     		$retorno[] = $cap;
//     	}
//     	return $retorno;
    }
    
        
}