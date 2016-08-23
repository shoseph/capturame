<?php
namespace Capturada\Table;
use Capturada\Entity\BatalhaCapturada;

use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_batalha_capturada. 
 */
class BatalhaCapturadaTable extends CapturaTable{
    
    protected $table ='cp_batalha_capturada';
    protected $key = 'id_batalha_capturada';
    
    /**
     * Método que calcula a quantidade de capturadas cadastradas em uma batalha.
     * @param Integer $idUsuario identificador do usuário.
     */
    public function getQuantidadeCapturadasEmBatalha($idUsuario)
    {
        $select = $this->sql->select()
            ->columns(array(new Expression('count(*) as total')))
            ->join(array('uc' => 'cp_usuario_capturada')
                , "uc.id_capturada = {$this->table}.id_capturada"
                , array()
            )
            ->join(array('cp' => 'cp_capturada')
                , "cp.id_capturada = uc.id_capturada"
                , array()
            )
            ->where(array('uc.id_usuario' => $idUsuario))
        ;
        return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que salva uma batalha capturada, registrando assim
     * uma nova foto na batalha.
     * @param BatalhaCapturada $batalhaCapturada objeto de batalha capturada esse contém
     * todos os dados a serem inseridos na tabela.
     * @throws \Exception
     */
    public function save(BatalhaCapturada $batalhaCapturada)
    {
    	if ($batalhaCapturada->getId() == 0) {
    		$this->insert($batalhaCapturada->toArray());
    	} elseif ($batalhaCapturada->getId()) {
    		$this->update($batalhaCapturada->toArray(),array(
    				$this->key => $batalhaCapturada->getId(),
    		));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
    /**
     * Método que retorna as capturadas não cadastradas nas batalhas.
     * @param Integer $idUsuario Identificador do usuário
     * @param Integer $limit Limit.
     * @param Integer $offset Offset.
     * @return \Extended\Table\Ambigous
     */
    public function getCapturadasNaoCadastradas($idUsuario, $limit, $offset)
    {
        $select = $this->sql->select()->columns(array())
            ->join(
                array('uc' => 'cp_usuario_capturada'),
                "uc.id_capturada = {$this->table}.id_capturada",
                array('*'),
                'right'
            )
            ->join(
                array('cp' => 'cp_capturada'),
            	"cp.id_capturada = uc.id_capturada",
                array('*'),
                'right'
            )
            ->where(array('uc.id_usuario' => $idUsuario))
            ->where(array('uc.id_capturada not in(select id_capturada from bancocaptura.cp_batalha_capturada where id_usuario = ?)' => $idUsuario))
            ->limit($limit)
            ->offset($offset)
            ;
            return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a capturada da pagina e sua anterior e proxima
     * se existir.
     * @param Integer $limit Limit.
     * @param Integer $offset Offset.
     */
    public function getCapturadasNaBatalha($batalha, $limit, $offset)
    {
        $select = $this->sql->select()
            ->join(
                array('ba' => 'cp_batalha'),
                "ba.id_batalha = {$this->table}.id_batalha",
                array('*')
            )
            ->join(
                array('uc' => 'cp_usuario_capturada'),
                "uc.id_capturada = {$this->table}.id_capturada",
                array()
            )
            ->join(
                array('cp' => 'cp_capturada'),
            	"cp.id_capturada = uc.id_capturada",
                array('*')
            )
            ->where(array('ba.id_batalha = ?' => $batalha))
            ->order("{$this->table}.id_batalha_capturada ASC")
            ->limit($limit)
            ->offset($offset)
            ;
            return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de capturadas em uma batalha atual.
     * @param Integer $idUsuario identificador do usuário em questão.
     * @param Integer $idBatalha identificador da batalha em questão.
     * @return number
     */
    public function getQuantidadeCapturadasEmBatalhaAtual($idUsuario, $idBatalha)
    {
    	$select = $this->sql->select()
    	->columns(array(new Expression('count(*) as total')))
    	->join(array('uc' => 'cp_usuario_capturada')
    			, "uc.id_capturada = {$this->table}.id_capturada"
    			, array()
    			)
    	->join(array('cp' => 'cp_capturada')
    			, "cp.id_capturada = uc.id_capturada"
    			, array()
    	)
    	->join(
    	     array('ba' => 'cp_batalha'),
    	     "ba.id_batalha = {$this->table}.id_batalha",
    	     array()
    	)
    	->where(array('uc.id_usuario' => $idUsuario))
    	->where(array('ba.id_batalha' => $idBatalha))
    	->where(array("ba.aberta =  1"))
    	;
    	return (int) $this->fetchAll($select)->current()->total;
    }
    /**
     * Método que retorna a quantidade de capturadas em uma batalha em questão.
     * @param Integer $idBatalha identificador da batalha em questão.
     * @return number
     */
    public function getQuantidadeTotalCapturadasEmBatalha($idBatalha)
    {
    	$select = $this->sql->select()
    	->columns(array(new Expression('count(*) as total')))
    	->join(array('uc' => 'cp_usuario_capturada')
    			, "uc.id_capturada = {$this->table}.id_capturada"
    			, array()
    			)
    	->join(array('cp' => 'cp_capturada')
    			, "cp.id_capturada = uc.id_capturada"
    			, array()
    	)
    	->join(
    	     array('ba' => 'cp_batalha'),
    	     "ba.id_batalha = {$this->table}.id_batalha",
    	     array()
    	)
    	->where(array('ba.id_batalha' => $idBatalha))
    	->where(array("ba.aberta =  1"))
    	;
    	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retorna a quantidade de capturadas em todas as batalhas abertas.
     */
    public function getQuantidadeTotalCapturadasEmBatalhasAbertas()
    {
    	$select = $this->sql->select()
    	->columns(array(new Expression('count(*) as total')))
    	->join(array('uc' => 'cp_usuario_capturada')
    			, "uc.id_capturada = {$this->table}.id_capturada"
    			, array()
    			)
    	->join(array('cp' => 'cp_capturada')
    			, "cp.id_capturada = uc.id_capturada"
    			, array()
    	)
    	->join(
    	     array('ba' => 'cp_batalha'),
    	     "ba.id_batalha = {$this->table}.id_batalha",
    	     array()
    	)
    	->where(array("ba.aberta =  1"))
    	;
    	return (int) $this->fetchAll($select)->current()->total;
    }

    /**
     * Método que retorna a ultima capturada de uma batalha.
     * @param Integer $idBatalha identificador da batalaha.
     */
    public function getUltimaCapturada($idBatalha)
    {
    	$select = $this->sql->select()
        	->columns(array(new Expression('max(id_batalha_capturada) as max')))
        	->where(array('id_batalha = ?' => $idBatalha))
    	;
    	return (int) $this->fetchAll($select)->current()->max;
    }
    
    public function cadastrarPeguei()
    {
        
    }
    
}
