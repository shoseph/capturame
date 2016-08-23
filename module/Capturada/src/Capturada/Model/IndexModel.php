<?php
namespace Capturada\Model;

use Common\Exception\ArquivoNaoInseridoException;
use Common\Exception\TagVinculadaException;
use Capturada\Entity\CapturadaTag;
use Capturada\Entity\Tag;
use Capturada\Entity\UsuarioCapturada;
use User\Auth\CapturaAuth;
use Zend\Form\Form;
use Capturada\Table\CapturadaTable;
use Capturada\Entity\Capturada;
use Extended\Model\CapturaModel;
class IndexModel extends CapturaModel
{
    
    /**
     * Método que retorna todas as capturadas de um determinado usuário.
     * @param String $idUsuario idenfiticador de busca é o id do usuário.
     */
    public function getCapturadas($idUsuario, $pagina, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $capturadas =  $this->getTable('UsuarioCapturada','capturada')->getCapturadas($idUsuario, $limit, $offset);
        $retorno = array();
        foreach($capturadas as $capturada){
            $cap = Capturada::getNewInstance()->fillInArray($capturada);
            $cap->user = $idUsuario;
            $cap->tags = $this->getTags($cap->id_capturada);
            $retorno[] = $cap;
        }
        return $retorno;
    }
    
    /**
     * Método que retorna as mais curtirdas do Captura.Me
     * @param Integer $pagina pagina em questão
     * @param Integer $limit quantidade a ser retornada
     */
    public function getMaisCurtidas($pagina, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $capturadas =  $this->getTable('UsuarioCapturada','capturada')->getMaisCurtidas($limit, $offset);
        
        $retorno = array();
        foreach($capturadas as $capturada){
            $cap = Capturada::getNewInstance()->fillInArray($capturada);
            $cap->user = $capturada->id_usuario;
            $cap->tags = $this->getTags($cap->id_capturada);
            $retorno[] = $cap;
        }
        return $retorno;
    }
    
    /**
     * Método que retorna as mais novas imagens do Captura.Me
     * @param Integer $pagina pagina em questão
     * @param Integer $limit quantidade a ser retornada
     */
    public function getNovasCapturadas($pagina, $limit = 25)
    {
        $offset = ($pagina * $limit) - $limit;
        $capturadas =  $this->getTable('UsuarioCapturada','capturada')->getNovasCapturadas($limit, $offset);
        $retorno = array();
        foreach($capturadas as $capturada){
            $cap = Capturada::getNewInstance()->fillInArray($capturada);
            $cap->user = $capturada->id_usuario;
            $cap->tags = $this->getTags($cap->id_capturada);
            $retorno[] = $cap;
        }
        return $retorno;
    }
    
    /**
     * Método que retorna os dados referentes a visualização de uma capturada.
     * @param Integer $idUsuario idenfiticador de busca é o id do usuário.
     * @param Integer $capturada Identificador de uma capturada.
     * @param Integer $limit Limite de campos.
     */
    public function getVisualizacaoCapturada($idUsuario, $capturada, $limit = 2)
    {
        $capturadas =  $this->getTable('UsuarioCapturada','capturada')->getVisualizacaoCapturada($idUsuario, $capturada, $limit);
        $retorno = array();
        foreach($capturadas as $capturada){
            
            $cap = new Capturada();
            $cap->user = $idUsuario;
            $cap->exchangeArray($capturada);
            $usuarioCapturada = new UsuarioCapturada();
            $usuarioCapturada->exchangeArray($capturada);
            $usuarioCapturada->setCapturada($cap);
            $retorno[] = $usuarioCapturada;

        }
        return $retorno;
    }
    
    /**
     * Método que retorna a capturada.
     * @param String $idCapturada idenfiticador de procura do arquivo.
     * @param String $idUsuario idenfiticador de procura do arquivo.
     */
    public function getCapturada($idCapturada, $idUsuario)
    {
        $retorno = $this->getTable('UsuarioCapturada', 'Capturada')->getCapturada($idUsuario, $idCapturada)->current();
        return $retorno ? Capturada::getInstance()->fillInArray($retorno)->setUser($idUsuario) : null; 
    }
    
    /**
     * Método que retorna a quantidade de capturadas de um usuário.
     * @param String $idUsuario idenfiticador de procura do arquivo.
     */
    public function getQuantidadeCapturadas($idUsuario)
    {
        return $this->getTable('UsuarioCapturada','capturada')->getQuantidadeCapturadas($idUsuario);
    }
    
    /**
     * Método que retorna a quantidade de capturadas geral.
     */
    public function getQuantidadeTotalCapturadas()
    {
        return $this->getTable('UsuarioCapturada','capturada')->getQuantidadeTotalCapturadas();
    }
    
    /**
     * Método que retorna a quantidade de capturadas no site Captura.Me que 
     * foram votadas.
     */
    public function getQuantidadeMaisCurtidas()
    {
        return $this->getTable('UsuarioCapturada','capturada')->getQuantidadeMaisCurtidas();
    }
    
    /**
     * Método que retorna várias fotos randomicas
     * @param Integer $quantidade quantidade de fotos que devem
     * ser retornadas.
     */
    public function getRandomCapturadas($quantidade)
    {
        $fotosRandomicas = $this->getTable('UsuarioCapturada', 'Capturada')->getRandomCapturadas($quantidade);
        $retorno = array();
        foreach($fotosRandomicas as $foto){
            $capturada = new Capturada();
            $capturada->exchangeArray($foto);
            $capturada->user = $foto->id_usuario;
            $retorno[] = $capturada;
        }
        return $retorno;
    }
    
    /**
     * Método que altera uma capturada.
     * @param \Zend\Form $form Formulário.
     */
    public function alterarCapturada($form)
    {
        $capturada = new Capturada();
        $capturada->exchangeArray($form->getData());
        return $this->getTable('Capturada', 'Capturada')->save($capturada);
    }
    
    /**
     * Método que verifica se existe a tag, caso não exista
     * é adicionada ao banco.
     * @param Form $form
     * @return Integer retorna o id da tag. 
     * @deprecated
     */
    public function adicionarTag(Form $form)
    {
        $tag = new Tag();
        $tag->exchangeArray($form->getData());
        $retorno = $this->getTable('tag','capturada')->getTagPorNome($tag);
        if(!$retorno->count()){
            return $this->getTable('tag','capturada')->save($tag);
        } else {
            return $retorno->current()->id_tag;
        }
    }
        
    /**
     * Método que vincula uma tag a capturada.
     * @param Integer $idTag Identificador da tag.
     * @param Integer $idCapturada Identificador da capturada.
     * @deprecated
     */
    public function vincularTag($idTag, $idCapturada)
    {
        $capturadaTag = new CapturadaTag();
        $capturadaTag->id_capturada = $idCapturada;
        $capturadaTag->id_tag = $idTag;
        
        $retorno = $this->getTable('capturadaTag','capturada')->getCapturadaTag($capturadaTag);
        if($retorno->count()){
            throw new TagVinculadaException();
        }
        $this->getTable('capturadatag','capturada')->save($capturadaTag);
        
    }
    /**
     * Método que retorna as tags de uma capturada
     * @param Integer $idCapturada Identificador da capturada.
     */
    public function getTags($idCapturada)
    {
        $capturadaTag = new CapturadaTag();
        $capturadaTag->id_capturada = $idCapturada;
        $retorno = array();
        $tags = $this->getTable('capturadaTag','capturada')->getCapturadaTag($capturadaTag);
        foreach($tags as $capturadaTag){
            $tag = new Tag();
            $tag->exchangeArray($capturadaTag);
            $tag->exchangeArray($this->getTable('tag','capturada')->getTag($tag)->current());
            $retorno[] = $tag;
        }
        return $retorno;
    }
    
    /**
     * Método que executa a ação de aumentar um ponto na quantidade
     * de pegou de uma capturada
     * @param Integer $capturada Identificador de uma capturada.
     * @param Integer $usuario Identificador de um capturador.
     */
    public function setPegou($capturada, $usuario)
    {
        $retorno = $this->getTable()->getCapturada($capturada);
        $capturada = new Capturada();
        $capturada->user = $usuario;
        $capturada->exchangeArray($retorno->current());
        $capturada->pegou += constant('VALOR_CURTIR_IMAGEM');
        $this->getTable('capturada')->save($capturada);
        return constant('VALOR_CURTIR_IMAGEM');
    }
    /**
     * Método que executa a ação de aumentar um ponto na quantidade
     * de não pegou de uma capturada
     * @param Integer $capturada Identificador de uma capturada.
     * @param Integer $usuario Identificador de um capturador.
     */
    public function setNaoPegou($capturada, $usuario)
    {
        $retorno = $this->getTable()->getCapturada($capturada);
        $capturada = new Capturada();
        $capturada->user = $usuario;
        $capturada->exchangeArray($retorno->current());
        $capturada->naoPegou += constant('VALOR_CURTIR_IMAGEM');
        $this->getTable('capturada')->save($capturada);
        return constant('VALOR_CURTIR_IMAGEM');
    }
    
}