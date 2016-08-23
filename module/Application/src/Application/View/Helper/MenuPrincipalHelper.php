<?php
namespace Application\View\Helper;

use User\Auth\CapturaAuth;

use Capturada\Entity\Notificacao;

use Extended\View\CapturaViewHelper;

class MenuPrincipalHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        $config = array();
        $config['view'] = $this->getView();
        
        if($user = CapturaAuth::getInstance()->getUser()){
            
            $quantidadeMaisCurtidas = $this->getModel('Notificacao', 'Capturada')->getUltimaNotificacaoUsuario(1, $user->id_usuario)->current();
            $quantidadeMaisCurtidas = $quantidadeMaisCurtidas ? $quantidadeMaisCurtidas->quantidade : 0;
            $config['maisCurtidas'] = $this->getModel('AcaoUsuario', 'user')->getQuantidadeMaisCurtidasMes() - $quantidadeMaisCurtidas;
            
            
            $quantidadeNovasCapturadas = $this->getModel('Notificacao', 'Capturada')->getUltimaNotificacaoUsuario(2, $user->id_usuario)->current();
            $quantidadeNovasCapturadas = $quantidadeNovasCapturadas ? $quantidadeNovasCapturadas->quantidade : 0;
            $config['enviadas'] = $this->getModel('AcaoUsuario', 'user')->getQuantidadeEnviadasMes() - $quantidadeNovasCapturadas;

            $quantidadeNovasBatalhas = $this->getModel('Notificacao', 'Capturada')->getUltimaNotificacaoUsuario(3, $user->id_usuario)->current();
            $quantidadeNovasBatalhas = $quantidadeNovasBatalhas ? $quantidadeNovasBatalhas->quantidade : 0;
            $config['batalhas'] = $this->getModel('Batalha', 'capturada')->getQuantidadeTotalCapturadasEmBatalhasAbertas() - $quantidadeNovasBatalhas;
            
            $quantidadeNovosUsuarios = $this->getModel('Notificacao', 'Capturada')->getUltimaNotificacaoUsuario(4, $user->id_usuario)->current();
            $quantidadeNovosUsuarios = $quantidadeNovosUsuarios ? $quantidadeNovosUsuarios->quantidade : 0;
            $config['usuarios'] = $this->getModel('User', 'user')->getQuantidadeUsuariosNovos() - $quantidadeNovosUsuarios;
            
            $quantidadeNovidades = $this->getModel('Notificacao', 'Capturada')->getUltimaNotificacaoUsuario(5, $user->id_usuario)->current();
            $quantidadeNovidades = $quantidadeNovidades ? $quantidadeNovidades->quantidade : 0;
            $config['novidades'] = $this->getModel('AcaoUsuario', 'user')->getQuantidadeNovidadesMes() - $quantidadeNovidades;
        }
            
        return $this->renderPartial('menu-principal', $config, 'Application', 'Index');
    }
    
}