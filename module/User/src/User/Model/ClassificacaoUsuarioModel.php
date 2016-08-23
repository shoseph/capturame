<?php
namespace User\Model;
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

class ClassificacaoUsuarioModel extends CapturaModel{
    
    /**
     * Método que retorna uma ação
     * @param String $nome nome da ação
     */
    public function getUltimaClassificacaoUsuario($idUsuario)
    {
        $retorno = $this->getTable('classificacaoUsuario','user')->getUltimaClassificacao($idUsuario);
        if(!$retorno->count()){
            $retorno = $this->getTable('classificacao','user')->getPrimeiraClassificacao();
            $data = $retorno->current();
            $data['ano'] = date('Y');
            $data['mes'] = date('m');
            $data['id_usuario'] = $idUsuario;
        } else {
            $data = $retorno->current();
        }
        $obj = ClassificacaoUsuario::getNewInstance()->fillinArray($data);
        $obj->classificacao = Classificacao::getNewInstance()->fillInArray($data);
        
        return $obj;
    }
    
    /**
     * Método que atualiza a classificação do usuário
     * @param Integer $idUsuario identificador do usuario
     * @param Classificacao $classificacao Objeto do tipo Classificação
     * @param Integer $somatorioPontosMensais Somatório dos pontos atuais
     * @param Integer $mes mes em que ocorre a ação
     * @param Integer $ano ano em que ocorre a ação
     */
    public function atualizarClassificacaoUsuario($idUsuario, $classificacao, $somatorioPontosMensais, $ano, $mes)
    {
        if($somatorioPontosMensais > $classificacao->classificacao->pontos){

            // Número indica a quantidade de usuários que se transformaram em master quando foram os primeiros a chegarem na classificação ouro
            $quantidadePrimeirosOuro = 10;
            $vagaMaster = $this->getTable('classificacaoUsuario','user')->getCountClassificacaoOuro($mes, $ano)->current()->total < $quantidadePrimeirosOuro ? true : false;
            
            // pegando a proxima classificação
            $rs = $this->getTable('classificacao','user')->getProximaClassificacao($somatorioPontosMensais);
            $arrayClassificacao = $rs->toArray();
            
            // executando a regra se o próximo for a classificação ouro e for possível ser a vaga do master do mês
            $classificacao->id_classificacao = $arrayClassificacao[0]['nome'] == 'ouro' ? $vagaMaster ? $arrayClassificacao[1]['id_classificacao'] : $arrayClassificacao[0]['id_classificacao'] : $arrayClassificacao[0]['id_classificacao'];   
            $this->getTable('classificacaoUsuario','user')->save($classificacao);
            
        } 
    }
}