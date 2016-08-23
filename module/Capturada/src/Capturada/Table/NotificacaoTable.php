<?php
namespace Capturada\Table;

/**
 * Método que tem as configurações de uma table da tabela cp_capturada. 
 */
use Zend\Db\Sql\Expression;

use Capturada\Entity\Notificacao;

use Extended\Table\CapturaTable;

class NotificacaoTable extends CapturaTable{
    
    protected $table ='cp_notificacao';
    protected $key = 'id_notificacao';
            
    /**
     * Método que salva uma notificacao.
     * @param Notificacao $notificacao objeto do tipo notificação.
     */
    public function save(Notificacao $notificacao)
    {
    	if ($notificacao->getId() == 0) {
    		$this->insert($notificacao->toArray());
    		return $this->getLastInsertValue();
    	} elseif ($notificacao->getId()) {
    		$this->update($notificacao->toArray(),array('id_notificacao' => $notificacao->getId(),));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    }
    
    /**
     * Método que retorna a ultima notificação do usuário.
     */
    public function getNotificacao(Notificacao $notificacao)
    {
    	$select = $this->sql->select()
    	->where(array('id_usuario' => $notificacao->id_usuario))
    	->where(array('mes' => $notificacao->mes))
    	->where(array('ano' => $notificacao->ano))
    	->where(array('id_tipo_notificacao' => $notificacao->id_tipo_notificacao))
    	->order(new Expression("{$this->key} DESC"));
    
    	return $this->fetchAll($select);
    }
}
