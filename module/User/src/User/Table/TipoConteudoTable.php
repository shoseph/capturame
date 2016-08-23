<?php 
namespace User\Table;
use User\Entity\TipoConteudo;

use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;
use User\Entity\User as User;

class TipoConteudoTable extends CapturaTable
{
    protected $table ='cp_tipo_conteudo';
    protected $key = 'id_conteudo';

    /**
     * Método que retorna qual é o tipo do conteudo em questão
     * @param TipoConteudo $tipoConteudo Objeto do tipoConteudo
     */
    public function getTipoConteudo(TipoConteudo $tipoConteudo)
    {
    	$select = $this->sql->select()->where($tipoConteudo->toArray());
    	return $this->fetchAll($select);
    }
    
}