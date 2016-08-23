<?php
namespace Capturada\Model;

use User\Auth\CapturaAuth;

use Capturada\Entity\Notificacao;

use Extended\Model\CapturaModel;

class NotificacaoModel extends CapturaModel
{
    
    public function getNotificacaoUsuario(Notificacao $notificacao)
    {
        return $this->getTable('Notificacao', 'capturada')->getNotificacao($notificacao);
    }
    
    /**
     * Método que retorna a ultima instancia de notificacao do usuário logado
     * @param unknown_type $tipo
     */
    public function getUltimaNotificacaoUsuario($tipo, $usuario)
    {
        return $this->getNotificacaoUsuario(Notificacao::getInstance()->fillInArray(array(
    		'ano' => date('Y'),
    		'mes' => date('m'),
    		'id_usuario' => $usuario,
    		'id_tipo_notificacao' => $tipo
        )));
    }
    
    /**
     * Método que visualiza uma notificação.
     * @param Integer $tipoNotificacao identificador de tipo de uma notificação.
     */
    public function visualizarNotificacao($tipoNotificacao, $quantidade)
    {
        // quantidade de 
        $quantidade < 0 ? $quantidade = 0 :'';
        
        // cria o objeto notificação 
        $notificacao = Notificacao::getInstance()->fillInArray(array(
    		'ano' => date('Y'),
    		'mes' => date('m'),
    		'quantidade' => $quantidade,
    		'id_usuario' => CapturaAuth::getInstance()->getUser()->id_usuario,
    		'id_tipo_notificacao' => $tipoNotificacao
        ));
        
        // busca pela notificação cadastrada no banco, caso exista irá mostrar a quantidade de fotos já vistas
        $notificacaoUsuario = $this->getNotificacaoUsuario($notificacao)->current();
        if(!$notificacaoUsuario || $quantidade > $notificacaoUsuario->quantidade){
        	$this->adicionarNotificacao($notificacao);
        }
    }

    /**
     * Método que visualiza as mais curtidas
     */
    public function visualizarMaisCurtidas()
    {
        $tipoNotificacao = 1;
        $quantidade = $this->getModel('AcaoUsuario','user')->getQuantidadeMaisCurtidasMes();
        $this->visualizarNotificacao($tipoNotificacao, $quantidade);
    }
    
    /**
     * Método que visualiza as mais novas capturadas
     */
    public function visualizarNovasCapturadas()
    {
        $tipoNotificacao = 2;
        $quantidade = $this->getModel('AcaoUsuario','user')->getQuantidadeEnviadasMes();
        $this->visualizarNotificacao($tipoNotificacao, $quantidade);
    }
    
    /**
     * Método que visualiza as novas batalhas
     */
    public function visualizarNovasCapturadasEmBatalha()
    {
        $tipoNotificacao = 3;
        $quantidade = $this->getModel('Batalha','Capturada')->getQuantidadeTotalCapturadasEmBatalhasAbertas();
        $this->visualizarNotificacao($tipoNotificacao, $quantidade);
    }
    
    /**
     * Método que visualiza as novos usuários
     */
    public function visualizarNovosUsuarios()
    {
        $tipoNotificacao = 4;
        $quantidade = $this->getModel('User','user')->getQuantidadeUsuariosNovos();
        $this->visualizarNotificacao($tipoNotificacao, $quantidade);
    }
    
    /**
     * Método que visualiza as novidades
     */
    public function visualizarNovidades()
    {
        $tipoNotificacao = 5;
        $quantidade = $this->getModel('AcaoUsuario', 'user')->getQuantidadeNovidadesMes();
        $this->visualizarNotificacao($tipoNotificacao, $quantidade);
    }
    
    /**
     * Métoo que adiciona uma notificação
     * @param Notificacao $notificacao objeto do tipo notificação
     */
    public function adicionarNotificacao(Notificacao $notificacao)
    {
        $this->getTable('Notificacao', 'capturada')->save($notificacao);
    }
    
}
