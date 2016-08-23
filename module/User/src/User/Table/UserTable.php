<?php 
namespace User\Table;
use Zend\Db\Sql\Expression;

use Extended\Table\CapturaTable;
use User\Entity\User as User;

class UserTable extends CapturaTable
{
    protected $table ='cp_usuario';
    protected $key = 'id_usuario';

    /**
     * Método que procura por um usuário inativo.
     * @param string $idUsuario numero do id do usuario.
     * @return \Extended\Table\Ambigous
     */
    public function findInativo($idUsuario)
    {
        $select = $this->sql->select()
                       ->where(array('id_usuario' => $idUsuario))
                       ->where(array('(ativo = false or ativo is null)'))
        ;
        return $this->fetchAll($select);
    }
    
    /**
     * Método que atualiza o usuário que está a prestes a ser ativado.
     * @param User $user Objeto retornado caso ele for inativo.
     */
    public function ativarUsuario($user)
    {
        $user = $user->current();
        return $this->update(array('ativo' => true),array('id_usuario' => $user->id_usuario));
    }
    
    /**
     * Método que retorna um RowSet informando se existe o usuário
     * se o mesmo existir é retornado os dados referentes a ele.
     * @param User $user
     */
    public function autenticar($user)
    {
        $select = $this->sql->select()->where(array('senha' => md5($user->senha)));
        if($user->login){
            $select->where(array('login' => $user->login));
        }
        if($user->email){
            $select->where(array('email' => $user->email));
        }
        return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna uma instância de usuário.
     * @param String $idUsuario identificador de um usuário.
     */
    public function getUsuario($idUsuario)
    {
    	$select = $this->sql->select()
    	->where(array('id_usuario' => $idUsuario))
    	;
    	return $this->fetchAll($select);
    }

    /**
     * Método que insere/atualiza um novo usuário
     * @param User $user Usuário.
     * @throws \Exception
     */
    public function save(User $user)
    {
        if ($user->getId() == 0) {
            $user->senha = md5($user->senha);
            $this->insert($user->toArray());
            return $this->getLastInsertValue();
        } elseif ($this->getUsuario($user->getId())) {
            $this->update(
                $user->toArray(),
                array(
                    'id_usuario' => $user->getId(),
                )
            );
        } else {
            throw new \Exception('Erro 001: Por favor contactar a administração.');
        }
    }

    /**
     * Método que procura por um usuário
     * @param User $user
     */
    public function findUsuario(User $user)
    {
    	$select = $this->sql->select()->where($user->toArray());
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que remove um usuário.
     * @param User $user
     */
    public function deleteUsuario(User $user)
    {
        $this->delete(array(
            'login' => $user->login,
        ));
    }
    
    /**
     * Método que retorna os usuários mais novos.
     * @param inicio da busca $limit.
     * @param quantidade de campos por vez $offset.
     */
    public function getUsuariosNovos($limit, $offset, $comFotos)
    {
        $select = $this->sql->select()->order('nome')->limit($limit)->offset($offset);
        
        if($comFotos){
            $select->join(array('cp' => 'cp_usuario_capturada')
                , "{$this->table}.id_usuario = cp.id_usuario"
                , array()
            )->columns(array(new Expression('count(*) as total'), '*'))
            ->group("{$this->table}.id_usuario");
        }
        
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna os melhores usuários do site.
     * @param inicio da busca $limit.
     * @param quantidade de campos por vez $offset.
     */
    public function getMelhoresUsuarios($limit, $offset)
    {
        
        $select = $this->sql->select()
            ->columns(array(new Expression("max(pp.soma) as soma, {$this->table}.nome as nomeUsuario, login, senha, cpf, email, ativo, tipo, max(rep.nome_reputacao) AS nome_reputacao, max(rep.pontos) AS pontos, max(rep.intervalo) AS intervalo, max(rep.id_reputacao) as id_reputacao")))
            ->join(
                array('pp' => 'cp_ponto_permanente'),
                "pp.id_usuario = {$this->table}.id_usuario",
                array('id_ponto_permanente','id_usuario', 'id_acao', 'mes','ano')
            )
            ->join(
                array('rep' => 'cp_reputacao'),
                'pp.id_reputacao = rep.id_reputacao',
                array()
            )
            ->order(new Expression('max(pp.soma) DESC'))
            ->group("{$this->table}.id_usuario")
            ->limit($limit)
            ->offset($offset);
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna os melhores usuários do mês atual.
     * @param inicio da busca $limit.
     * @param quantidade de campos por vez $offset.
     */
    public function getMelhoresUsuariosMensais($limit, $offset)
    {
        $select = $this->sql->select()
            ->columns(array(new Expression('max(pm.soma) as soma'), "*"))
            ->join(
                array('pm' => 'cp_ponto_mensal'),
                "pm.id_usuario = {$this->table}.id_usuario",
                array('id_ponto_mensal','id_usuario', 'id_acao', 'id_classificacao')
            )
            ->order(new Expression('max(pm.soma) DESC'))
            ->group("{$this->table}.id_usuario")
            ->limit($limit)
            ->offset($offset);
        
    	return $this->fetchAll($select);
    }
    
    /**
     * Método que retorna a quantidade de usuarios novos no captura.me
     * @return number total de usuarios novos
     */
    public function getQuantidadeUsuariosNovos()
    {
    	$select = $this->sql->select()
    	               ->columns(array(new Expression('count(*) as total')))
    	               ->where("DATE_FORMAT(data_cadastro,'%m') = DATE_FORMAT(CURRENT_TIMESTAMP,'%m')")
    	               ->where("DATE_FORMAT(data_cadastro,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP,'%Y')")
    	;
    	return (int) $this->fetchAll($select)->current()->total;
    }
}