<?php
namespace User\Model;
use User\Entity\PontoMensal;

use User\Entity\PontoPermanente;

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

class PontoMensalModel extends CapturaModel{

    /**
     * Método que adiciona uma ação de um determinado usuário
     * @param Integer $idUsuario identificador do usuário
     * @param Acao $acao Objeto da ação
     * @param Classificacao $classificacao Objeto da classificação
     */
    public function adicionarPontoMensal($idUsuario, $acao, $classificacao)
    {
        // pegando a soma de todos os pontos do usuário atual
        $soma = $this->getTable('pontoMensal', 'user')->getSomaPontosUsuario($idUsuario);
        $soma = $soma->count() ? $soma->current()->soma : 0; 
        $soma += $acao->ponto_mensal * $classificacao->multiplicador;
        
        // criando o objeto ponto permanente
        $pontoPermanente = PontoMensal::getInstance()->fillinArray(array('id_usuario' => $idUsuario, 'id_acao' => $acao->id_acao, 'id_classificacao' => $classificacao->id_classificacao, 'soma' => $soma));

        // adicionando ponto mensal
        $this->getTable('pontoMensal','user')->save($pontoPermanente);
         
        return $soma;
    } 
    
    /**
     * Método que retorna objeto ponto mensal de um determinado usuário.
     * @param Integer $idUsuario identificador de um usuário
     */
    public function getPontoMensalUsuario($idUsuario)
    {
    	return PontoMensal::getNewInstance()->fillInArray($this->getTable('pontoMensal','user')->getPontosUsuario($idUsuario)->current());
    }
    
    
}