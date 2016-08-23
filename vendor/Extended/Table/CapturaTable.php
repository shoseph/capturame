<?php
namespace Extended\Table;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;

use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;

/**
 * Classe que tem o básico de uma table no captura.me
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller
 */
abstract class CapturaTable extends AbstractTableGateway{
    
    protected $key;
    private $_config;
    private $_tableGateway;
    
    /**
     * Método que retorna o tableGateway.
     */
    public function getTableGateway(){
        
        if ($this->_tableGateway == null){
            $this->_tableGateway = new TableGateWay($this->getTable(),$this->adapter, new RowGatewayFeature($this->key)); 
        }
        return $this->_tableGateway;
    }
    
    /**
     * Método que retorna os modes de um adapter.
     * @return StdClass
     */
    public function getAdapterModes(){
        $adapter = $this->adapter;
        return (object) array('execute' => $adapter::QUERY_MODE_EXECUTE, 'prepare' => $adapter::QUERY_MODE_PREPARE);
    }
    
    /**
     * Método que seta as propriedades da classe instanciadora
     */
    private function setConfig()
    {
    	preg_match('/(?P<modulename>[A-Za-z]+)\\\Table\\\(?P<classname>[a-zA-Z]+)Table/', get_called_class(), $infoClass);
    	$this->_config = (object) array('module' => $infoClass['modulename'], 'class' => $infoClass['classname']);
    }
    
    /**
     * Método que retorna o path para instanciação do tipo de objeto de retorno.
     */
    private function getObjectPath($entity)
    {
        return "\\{$this->_config->module}\\Entity\\{$entity}";
    }
    
    /**
     * Método que constroi uma table.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter, $entity)
    {
        if( ($this->table == null && $var[] = '$table') | ($this->key == null && $var[] = '$key') ){
            throw new \Exception('Necessita setar um valor: <b>' . implode(' ', $var) .'</b> da classe <b>' . get_called_class() . '</b>');
        }
        
        $this->setConfig();
    	$this->adapter = $adapter;
    	$this->resultSetPrototype = new ResultSet();
    	$path = $this->getObjectPath($entity);
    	$this->resultSetPrototype->setArrayObjectPrototype(new $path());
    	$this->initialize();
    }
    
    /**
     * Método que retorna um array de objetos do tipo da tabela.
     * @param Array $where conjunto de condições para filtro.
     * @deprecated
     */
    public function getAll($where = null)
    {
         return $this->getTableGateway()->select($where);
    }
    
    /**
     * Método que retorna um array de objetos do tipo da tabela.
     * @param Array $where conjunto de condições para filtro.
     */
    public function findAll($where = null, $limit = null, $offset = null)
    {
        $select = $this->sql->select();
        if($where){ $select->where($where); }
        if($limit){ $select->limit($limit); }
        if($offset){ $select->offset($offset); }
        
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna o primeiro resultado em objeto do tipo da tabela.
     * @param Array $where conjunto de condições para filtro.
     */
    public function getRow($where = null){
        return $this->getTableGateway()->select($where)->current();
    }
    
    /**
     * Método que faz o devido fetchAll em uma query montada via sql parametro
     * @param \Zend\db\sql\Select $select retorno do $this->sql->select() da
     * table requisitada.
     * @return Ambigous <\Driver\StatementInterface, \Zend\Db\ResultSet\Zend\Db\ResultSet, \Zend\Db\Adapter\Driver\ResultInterface, unknown>
     */
    public function fetchAll(\Zend\db\sql\Select $select)
    {
        return $this->adapter->query(
              $this->sql->getSqlStringForSqlObject($select)
            , $this->getAdapterModes()->execute
        );
    }
    
    /**
     * Método que retorna uma instância do objeto limpo sem nenhum dado.
     * @return \Zend\Db\ResultSet\ArrayObject
     */
    public function getObject(){
        return $this->select()->getArrayObjectPrototype();
    }
    
}
