<?php
namespace User\Model;
use User\Entity\ReputacaoUsuario;

use User\Entity\Reputacao;

use User\Entity\Classificacao;

use User\Entity\ClassificacaoUsuario;

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

class ReputacaoUsuarioModel extends CapturaModel{
    
    /**
     * Método que retorna uma ação
     * @param String $nome nome da ação
     */
    public function getUltimaReputacaoUsuario($idUsuario)
    {
        $retorno = $this->getTable('reputacaoUsuario','user')->getUltimaReputacao($idUsuario);
        if(!$retorno->count()){
            $retorno = $this->getTable('reputacao','user')->getPrimeiraReputacao();
            $data = $retorno->current();
            $data['ano'] = date('Y');
            $data['mes'] = date('m');
            $data['id_usuario'] = $idUsuario;
        } else {
            $data = $retorno->current();
        }
        $obj = ReputacaoUsuario::getNewInstance()->fillinArray($data);
        $obj->reputacao = Reputacao::getNewInstance()->fillInArray($data);
    
        return $obj;
    }
    
    /**
     * Método que atualiza a reputação do usuário
     * @param Classificacao $reputacao Objeto do tipo Classificação
     * @param Integer $somatorioPontos Somatório dos pontosdo usuário
     */
    public function atualizarReputacaoUsuario($reputacao, $somatorioPontos)
    {
        if($somatorioPontos >= $reputacao->reputacao->getNumeroMaximoDePontos()){
            // pegando a proxima classificação
            $reputacao->id_reputacao = $this->getTable('reputacao','user')->getProximaReputacao($somatorioPontos)->current()->id_reputacao;   
            $this->getTable('reputacaoUsuario','user')->save($reputacao);
        } 
    }
    
}