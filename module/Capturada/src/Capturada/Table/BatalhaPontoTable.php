<?php
namespace Capturada\Table;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Expression;

use Capturada\Entity\BatalhaPonto;

use Extended\Table\CapturaTable;
/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
class BatalhaPontoTable extends CapturaTable{
    
    protected $table ='cp_batalha_ponto';
    protected $key = 'id_batalha_ponto';
    
    /**
     * Método que salva uma batalha ponto.
     * @param BatalhaPonto $batalha Objeto de entidade de uma capturada.
     * @throws \Exception
     */
    public function save(BatalhaPonto $batalha)
    {
    	if ($batalha->getId() == 0) {
    		$this->insert($batalha->toArray());
    	} elseif ($batalha->getId()) {
    		$this->update($batalha->toArray(),array(
    		    $this->key => $batalha->getId(),
    		));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
    /**
     * Método que retorna a quantidade de vezes que uma imagem foi
     * curtida.
     * @param unknown_type $batalhaCapturada
     * @return number
     */
    public function getPegouCapturada($batalhaCapturada)
    {
       	$select = $this->sql->select()->columns(array(new Expression('sum(pegou) as total')))->where(array('id_batalha_capturada = ? ' => $batalhaCapturada));
       	return (int) $this->fetchAll($select)->current()->total;
    }
    /**
     * Método que retorna a quantidade de vezes que uma imagem foi
     * curtida.
     * @param unknown_type $batalhaCapturada
     * @return number
     */
    public function getNaoPegouCapturada($batalhaCapturada)
    {
       	$select = $this->sql->select()->columns(array(new Expression('sum(naoPegou) as total')))->where(array('id_batalha_capturada = ? ' => $batalhaCapturada));
       	return (int) $this->fetchAll($select)->current()->total;
    }
    
    /**
     * Método que retona um objeto do tipo batalha ponto
     * @param BatalhaPonto $batalhaPonto
     */
    public function getBatalhaPonto(BatalhaPonto $batalhaPonto)
    {
        $select = $this->sql->select()->where($batalhaPonto->toArray());
        return $this->fetchAll($select);
    }
    /**
     * Método que retona um objeto do tipo batalha ponto
     * @param BatalhaPonto $batalhaPonto
     */
    public function getBatalhaPontoDoDia(BatalhaPonto $batalhaPonto)
    {
        $condicional = "DATE_FORMAT(cp_batalha_ponto.dtCadastro,?) = DATE_FORMAT(CURRENT_TIMESTAMP,'%e')";

        $select = $this->sql->select()
            ->where(array($condicional => '%e'))
            ->where(array('id_batalha_capturada' => $batalhaPonto->id_batalha_capturada))
            ->where(array('id_usuario_acao' => $batalhaPonto->id_usuario_acao))
        ;
        
        return $this->fetchAll($select);
    }
}
