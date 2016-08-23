<?php
namespace User\Model;
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

class PontoPermanenteModel extends CapturaModel{

    /**
     * Método que adiciona uma ação de um determinado usuário.
     * @param Integer $idUsuario identificador do usuário.
     * @param Acao $acao Objeto da ação.
     * @param Classificacao $classificacao Objeto da classificação.
     * @param Reputacao $reputacao Objeto da reputação.
     * @param Integer $ano ano em que ocorre a ação.
     * @param Integer $mes mes em que ocorre a ação.
     */
    public function adicionarPontoPermanente($idUsuario, $acao, $classificacao, $reputacao, $ano, $mes)
    {
        // pegando a soma de todos os pontos do usuário atual
        $soma = $this->getTable('pontoPermanente', 'user')->getSomaPontosUsuario($idUsuario);
        $soma = $soma->count() ? $soma->current()->soma : 0; 
        $soma += $acao->ponto_permanente * $classificacao->multiplicador;
        
        // criando o objeto ponto permanente
        $pontoPermanente = PontoPermanente::getInstance()->fillinArray(array('id_usuario' => $idUsuario, 'id_acao' => $acao->id_acao, 'id_reputacao' => $reputacao->id_reputacao, 'mes' => $mes, 'ano' => $ano, 'soma' => $soma));
        
        // adicionando ponto permanente
        $this->getTable('pontoPermanente','user')->save($pontoPermanente);
        
        return $soma;  
    } 
    
    
    /**
     * Método que retorna objeto ponto permanente de um determinado usuário.
     * @param Integer $idUsuario identificador de um usuário
     */
    public function getPontoPermanenteUsuario($idUsuario)
    {
        return PontoPermanente::getNewInstance()->fillInArray($this->getTable('pontoPermanente','user')->getPontosUsuario($idUsuario)->current());
    }
}