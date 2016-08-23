<?php
namespace Capturada\Table;
use Zend\Db\Sql\Expression;

use Capturada\Entity\UsuarioCapturada;
use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
class UsuarioCapturadaTable extends CapturaTable{
    
    protected $table ='cp_usuario_capturada';
    protected $key = array('id_usuario','id_capturada');


    /**
     * Método que retorna a quantidade de capturadas de um determinado usuário.
     * @param Integer $idUsuario Identificador de um usuário.
     */
    public function getQuantidadeCapturadas($idUsuario)
    {
    	$select = $this->sql->select()->columns(array(new Expression('count(*) as total')))->where(array('id_usuario' => $idUsuario));
    	return (int) $this->fetchAll($select)->current()->total;
    }
    /**
     * Método que retorna a quantidade de capturadas geral.
     */
    public function getQuantidadeTotalCapturadas()
    {
    	$select = $this->sql->select()->columns(array(new Expression('count(*) as total')));
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna a quantidade de capturadas no site Captura.Me que
     * foram votadas.
     */
    public function getQuantidadeMaisCurtidas()
    {
    	$select = $this->sql->select()
    	    ->columns(array(new Expression('count(*) as total')))
    	    ->join(
  	    		array('cp' => 'cp_capturada')
   	    		, "cp.id_capturada = {$this->table}.id_capturada"
   	    		, array()
            )->where(array('cp.pegou  > ?' => 0))
    	    ;
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna as mais curtidas do site Captura.Me.
     */
    public function getMaisCurtidas($limit = null, $offset = null)
    {
    	$select = $this->sql->select()
    	    ->join(
    		    	array('cp' => 'cp_capturada')
    			    , "cp.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
    	    )->where(array('cp.pegou  > ?' => 0))
    	    ->order(new Expression('cp.pegou - cp.naoPegou DESC'))
    	    ->group("{$this->table}.id_capturada")
    	;
    	
    	!$limit ?: $select->limit($limit);
    	!$offset ?: $select->offset($offset);
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna as mais novas imagens do Captura.Me.
     */
    public function getNovasCapturadas($limit = null, $offset = null)
    {
    	$select = $this->sql->select()
    	    ->join(
    		    	array('cp' => 'cp_capturada')
    			    , "cp.id_capturada = {$this->table}.id_capturada"
    	    , array('*')
    	    )->order(new Expression('cp.dtImagem DESC'))
    	    ->group("{$this->table}.id_capturada")
    	;
    	
    	!$limit ?: $select->limit($limit);
    	!$offset ?: $select->offset($offset);
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna todas as capturadas de um determinado usuário.
     * @param String $idUsuario idenfiticador de busca é o id do usuário.
     */
    public function getCapturadas($idUsuario,  $limit = null, $offset = null)
    {
        
        $select = $this->sql->select()
              ->join(
                   array('cp' => 'cp_capturada')
                 , "cp.id_capturada = {$this->table}.id_capturada"
                 , array('*')
              )->where(array("{$this->table}.id_usuario" => $idUsuario));
        
        !$limit ?: $select->limit($limit);
        !$offset ?: $select->offset($offset);

        return $this->fetchAll($select);
    }

    /**
     * Método que retorna todas as capturadas de um determinado usuário.
     * @param Integer $idUsuario idenfiticador de busca é o id do usuário.
     * @param Integer $capturada Identificador de uma capturada.
     * @param Integer $limit Limite de campos.
     */
    public function getVisualizacaoCapturada($idUsuario, $capturada, $limit)
    {
        
        $select = $this->sql->select()
              ->join(
                   array('cp' => 'cp_capturada')
                 , "cp.id_capturada = {$this->table}.id_capturada"
                 , array('*')
              )
              ->where(array("{$this->table}.id_usuario = ?" => $idUsuario))
              ->where(array("{$this->table}.id_capturada >= ?" => $capturada))
              ;
        
        !$limit ?: $select->limit($limit);

        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a capturada de um determinado usuário.
     * @param Integer $idUsuario idenfiticador de busca é o id do usuário.
     * @param Integer $capturada Identificador de uma capturada.
     */
    public function getCapturada($idUsuario, $capturada)
    {
        $select = $this->sql->select()->join(
            array('cp' => 'cp_capturada')
                , "cp.id_capturada = {$this->table}.id_capturada"
                , array('*')
            )->where(array("{$this->table}.id_usuario = ?" => $idUsuario))
            ->where(array("{$this->table}.id_capturada = ?" => $capturada))
        ;
        
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna o ultimo id do conjunto de imagens postadas pelo usuário.
     * @param Integer $idUsuario identificador de um usuário.
     * @return Integer Id da ultima imagem adicionada ou nulo se não existir.
     */
    public function getUltimaCapturada($idUsuario)
    {
    	$select = $this->sql->select()->columns(array(new Expression('max(id_capturada) as ultima')))->where(array('id_usuario' => $idUsuario));
    	return (int) $this->fetchAll($select)->current()->ultima;
    }
    
    /**
     * Método que retorna uma capturada randomicamente.
     * @param Integer $quantidade quantidade de imagens a serem carregadas.
     */
    public function getRandomCapturadas($quantidade)
    {
        $randomico = new \Zend\Db\Sql\Expression('RAND()');
        $select = $this->sql->select()
              ->join(
                   array('cp' => 'cp_capturada')
                 , "cp.id_capturada = {$this->table}.id_capturada"
                 , array('*')
              )
              
              ->order($randomico)
              ->limit($quantidade)
        ;
        return $this->fetchAll($select);
    }
}
