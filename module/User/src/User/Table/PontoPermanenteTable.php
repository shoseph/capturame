<?php 
namespace User\Table;
use User\Entity\PontoPermanente;

use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;

class PontoPermanenteTable extends CapturaTable
{
    protected $table ='cp_ponto_permanente';
    protected $key = 'id_ponto_permanente';
    
    /**
     * Método que retona a soma de pontos de um usuário
     * @param Integer $idUsuario identificador de um usuário
     */
    public function getSomaPontosUsuario($idUsuario)
    {
    	$select = $this->sql->select()->columns(array('soma'))
   		    ->where(array("{$this->table}.id_usuario" => $idUsuario))
   		    ->order(array('soma DESC'))
		;
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna um objeto do tipo PontosPermanentes 
     * de um usuário.
     * @param Integer $idUsuario identificador de um usuário.
     */
    public function getPontosUsuario($idUsuario)
    {
    	$select = $this->sql->select()
   		    ->where(array("{$this->table}.id_usuario" => $idUsuario))
   		    ->order(array('soma DESC'))
		;
    	return $this->fetchAll($select);
    }
    
    
    /**
     * Método que insere/atualiza um ponto permanente
     * @param PontoPermanente $pontoPermanente Objeto de ação do usuário.
     */
    public function save(PontoPermanente $pontoPermanente)
    {
    	if ($pontoPermanente->getId() == 0) {
    		$this->insert($pontoPermanente->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($this->getUsuario($pontoPermanente->getId())) {
    		$this->update(
    				$pontoPermanente->toArray(),
    				array('id_ponto_permanente' => $pontoPermanente->getId(),)
    		);
    	} else {
    		throw new \Exception('Erro 001: Por favor contactar a administração.');
    	}
    }
    
    

}