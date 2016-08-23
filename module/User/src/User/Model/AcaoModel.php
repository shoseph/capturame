<?php
namespace User\Model;
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

class AcaoModel extends CapturaModel{
    
    /**
     * Método que retorna uma ação
     * @param String $nome nome da ação
     */
    public function getAcao($nome)
    {
        $retorno = $this->getTable('acao')->findAll(array("nome like ?" => "%{$nome}%"));
        if(!$retorno->count()){
            return null;
        }
        return Acao::getNewInstance()->fillinArray($retorno->current());
    }
    
    
}