<?php
namespace User\Model;
use User\Entity\PontoMensal;

use User\Entity\Reputacao;

use User\Entity\Classificacao;

use User\Entity\PontoPermanente;

use Common\Exception\EmailInvalidoException;

use Common\Exception\SessaoMortaException;

use User\Auth\CapturaAuth;

use Common\Exception\CpfExistenteException;

use Common\Exception\LoginExistenteException;

use Common\Exception\UsuarioInvalidoException;

use User\Entity\User;
use Zend\Form\Form;
use Zend\Mail;
use Extended\Model\CapturaModel;
use Common\Exception\UsuarioInativoException;

class UserModel extends CapturaModel{
    
    /**
     * Método que insere um usuário
     * @param Form $form formulário a ser cadastrado
     */
    public function salvar(Form $form)
    {
        // TODO: tentar melhorar o processo do usuário que está sendo carregado agora
        $user = new User();
        $user->exchangeArray($form->getData());
        $user->ativo = false;
        $user->login = strtolower($user->login);
        return $this->getTable()->save($user);
    }
    /**
     * Método que salva os dados de um usuário
     * @param Form $form formulário de um ususário
     */
    public function atualizar(User $user)
    {
        return $this->getTable()->save($user);
    }
    
    /**
     * Método que verifica se já existe um login igual ou se tem já cadastrado o cpf
     * na base de dados do Captura.Me
     * @param User $user Usuário a ser criado
     * @throws LoginExistenteException Verifica a existência de um login igual
     * @throws CpfExistenteException Verifica a existência de um cpf igual
     */
    public function verificarExistente(User $user)
    {
        $retorno = $this->getTable()->select(array('login' => $user->getLogin()));
        if($retorno->count()){
            throw new LoginExistenteException();
        }
        $retorno = $this->getTable()->select(array('cpf' => $user->getCpf()));
        if($retorno->count()){
        	throw new CpfExistenteException();
        }
    }
    
    /**
     * Método que cria uma nova senha randomica e envia para o
     * email do usuário.
     * @param Form $form
     */
    public function esqueceuSenha(Form $form)
    {
        $usuarioBusca = $this->getTable()->findUsuario(User::getNewInstance()->fillInArray($form->getData()));
        if($usuarioBusca->count()){
            
            $user = User::getNewInstance()->fillInArray($usuarioBusca->current());
            $senhaGerada = $this->geraSenha(8,true,true,false);
            $user->senha = md5($senhaGerada);
            
            // modificar campo de senha do usuário
            $this->getTable('user')->save($user);
            
            // enviar email contendo a nova senha
            $this->emailEsqueceuSenha($user, $senhaGerada);
            
        } else {
            throw new EmailInvalidoException();
        }
    }
    
    /**
     * Método que redefine uma nova senha
     * @param Form $form
     */
    public function modificarSenha(Form $form)
    {
        $usuarioBusca = $this->getTable()->findUsuario(User::getNewInstance()->fillInArray(array('id_usuario' => CapturaAuth::getInstance()->getUser()->id_usuario)));
        if($usuarioBusca->count())
        {
            $user = User::getNewInstance()->fillInArray($usuarioBusca->current());
            $user->senha = md5($form->get('senha')->getValue());
            
            // modificar campo de senha do usuário
            $this->getTable('user')->save($user);
            
        } else {
            throw new SessaoMortaException();
        }
        
    }
    
   /**
    * Função para gerar senhas aleatórias
    *
    * @author    Thiago Belem <contato@thiagobelem.net>
    *
    * @param integer $tamanho Tamanho da senha a ser gerada
    * @param boolean $maiusculas Se terá letras maiúsculas
    * @param boolean $numeros Se terá números
    * @param boolean $simbolos Se terá símbolos
    *
    * @return string A senha gerada
    */
    public function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
    {
    	$lmin = 'abcdefghijklmnopqrstuvwxyz';
    	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$num = '1234567890';
    	$simb = '!@#$%*-';
    	$retorno = '';
    	$caracteres = '';
    
    	$caracteres .= $lmin;
    	if ($maiusculas) $caracteres .= $lmai;
    	if ($numeros) $caracteres .= $num;
    	if ($simbolos) $caracteres .= $simb;
    
    	$len = strlen($caracteres);
    	for ($n = 1; $n <= $tamanho; $n++) {
    		$rand = mt_rand(1, $len);
    		$retorno .= $caracteres[$rand-1];
    	}
    	return $retorno;
    }
    
    /**
     * Método que retorna um RowSet informando se existe o usuário
     * se o mesmo existir é retornado os dados referentes a ele.
     * @param User $user
     */
    public function autenticar(Form $form)
    {
        $user = new User();
        $data = $form->getData();
        if(preg_match('/@/', $data['login'])){
            $data['email'] = strtolower($data['login']);
            $data['login'] = null;
        } else {
            $data['login'] = strtolower($data['login']);
        }
        $user->exchangeArray($data);
        $retorno = $this->getTable()->autenticar($user);
        
        if(!$retorno->count()){
            throw new UsuarioInvalidoException();
        }
        $arrayObject = $retorno->current();
        if(!$arrayObject->ativo){
            throw new UsuarioInativoException();
        }
        $usuario = $this->getUsuario($arrayObject->id_usuario);
        return $usuario;
    }
    
    /**
     * Método que procura por um usuário inativo.
     * @param String $idUser identificador é o número do id do usuário
     */
    public function findInativo($idUser)
    {
        return $this->getTable()->findInativo($idUser);
    }

    /**
     * 
     * Método que executa a ação de ativar um uruário.
     * @param User $user Objeto retornado caso ele for inativo.
     */
    public function ativarUsuario($user)
    {
        return $this->getTable()->ativarUsuario($user);
    }

    /**
     * Método que envia um email para o usuário que clicou no
     * botão esqueci minha senha.
     * @param User $user Objeto do tipo usuário.
     */
    public function emailEsqueceuSenha(User $user, $senhaGerada)
    {
       $html = "
            <h3>Olá Capturador {$user->nome}!</h3>
            <p>&nbsp;</p>
            <p>Seu login: <b>{$user->login}</b></p>
            <p>Sua nova senha: <b>{$senhaGerada}</b></p>
            <p>&nbsp;</p>
            <p>Sua senha foi resetada com sucesso, entre no <a href='http://captura.me'>Captura.Me</a> e em qualquer momento redefina para uma de sua escolha.</p>
            <p>&nbsp;</p>
            <p>Equipe Captura.me Brasil</p>
        ";
        
        $mail = new Mail\Message();
        $mail->addFrom(constant('NO-REPLY'), constant('NO-REPLY-NAME'))
        ->addTo($user->email, $user->nome)
        ->setSubject("[Captura.me] Redefinição de senha para usuário {$user->login}");
        
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage = new \Zend\Mime\Part($html);
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        $mail->setBody($bodyPart);
        $mail->setEncoding('UTF-8');
        
        $transport = new Mail\Transport\Smtp();
        $options   = new Mail\Transport\SmtpOptions(array(
        		'name' => 'captura.me',
        		'host' => 'smtp.gmail.com',
        		'connection_class' => 'login',
        		'port' => '465',
        		'connection_config' => array(
        				'ssl' => 'ssl', 
        		        'username' => 'no-reply@captura.me',
        				'password' => 'r@f@123492268946',
        		),
        ));
        
        $transport->setOptions($options);
        $transport->send($mail); 
    }
    
    /**
     * Método que envia um email após o cadatro do usuário,
     * este email contém dados para ativação do usuário.
     * @param Form $form formulário de cadastro do usuário.
     * @param Integer $idUSer Chave identificadora do novo usuário
     */
    public function emailCadastro(Form $form, $idUser)
    {
        
        $urlValidar = "{$_SERVER['HTTP_HOST']}/validar/" . base64_encode($idUser);
        
        $html = "
            <h3>{$form->get('login')->getValue()} seja bem vindo ao Captura.me!</h3>
            <p>Para você que está ancioso em entrar no captura.me, falta pouco!</p>
            <p>Estamos quase lá... falta apenas mais um passo!</p>
            <p>Necessita apenas verificar o seu usuário ativando a conta <a href='{$urlValidar}'>clicando aqui</a></p>
            <p>&nbsp;</p>
            <p>Caso não apareça o link abra no navegador essa url:{$urlValidar}</p>
            <p>&nbsp;</p>
            <p>Desde já agradecemos sua presença!</p>
            <p>Equipe Captura.me Brasil</p>
        ";
        
        $mail = new Mail\Message();
        $mail->addFrom(constant('NO-REPLY'), constant('NO-REPLY-NAME'))
        ->addTo($form->get('email')->getValue(), $form->get('nome')->getValue())
        ->setSubject("[Captura.me] Ativação do usuário {$form->get('login')->getValue()} no captura.me");
        
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage = new \Zend\Mime\Part($html);
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        $mail->setBody($bodyPart);
        $mail->setEncoding('UTF-8');
        
        $transport = new Mail\Transport\Smtp();
        $options   = new Mail\Transport\SmtpOptions(array(
        		'name' => 'captura.me',
        		'host' => 'smtp.gmail.com',
        		'connection_class' => 'login',
        		'port' => '465',
        		'connection_config' => array(
        				'ssl' => 'ssl', 
        		        'username' => 'no-reply@captura.me',
        				'password' => 'r@f@123492268946',
        		),
        ));
        
        $transport->setOptions($options);
        $transport->send($mail);
    }
    
    /**
     * Método que retorna a quantidade de fotos de um
     * determinado usuário.
     * @param String $idUsuario identificador do usuário.
     */
    public function getQuantidadeCapturadas($idUsuario)
    {
        return $this->getTable('UsuarioCapturada', 'Capturada')->getQuantidadeCapturadas($idUsuario);
    }
    
    /**
     * Método que retorna uma instância de usuário.
     * @param String $idUsuario identificador de um usuário.
     */
    public function getUsuario($idUsuario)
    {
        $retorno = $this->getTable('user','user')->getUsuario($idUsuario);
        $usuario = new User();
        $usuario->exchangeArray($retorno->current());
        $usuario->pontoPermanente = $this->getModel('pontoPermanente','user')->getPontoPermanenteUsuario($idUsuario);
        $usuario->pontoMensal = $this->getModel('pontoMensal','user')->getPontoMensalUsuario($idUsuario);
        return $usuario;
    }
    
    /**
     * Método que retorna uma instância de usuário.
     * @param User $usuario identificador de um usuário.
     */
    public function getUser(User $usuario)
    {
        $retorno = $this->getTable('User','User')->findUsuario($usuario);
        $user = User::getNewInstance()->fillInArray($retorno->current());
        if($retorno->count()){
            $user->pontoPermanente = $this->getModel('pontoPermanente','user')->getPontoPermanenteUsuario($user->id_usuario);
            $user->pontoMensal = $this->getModel('pontoMensal','user')->getPontoMensalUsuario($user->id_usuario);
        }
        return $user;
    }
    
    /**
     * Método que retorna os usuários mais novos.
     * @param pagina página que deve ser renderizada.
     */
    public function getUsuariosNovos($pagina = 0, $comFotos = true)
    {
        $limit = 100;
        $offset = 0;
        $retorno = array();
        $usuarios = $this->getTable()->getUsuariosNovos($limit, $offset, $comFotos);
        foreach($usuarios as $usuario){
            $user = new User();
            $user->exchangeArray($usuario);
            $user->total = $usuario->total;
            $retorno[] = $user;
        }
        return $retorno;
    }
    /**
     * Método que retorna a quantidade de usuários mais novos.
     */
    public function getQuantidadeUsuariosNovos()
    {
        return $this->getTable()->getQuantidadeUsuariosNovos();
    }
    
    
    /**
     * Método que retorna os melhores usuários do site.
     * @param pagina página que deve ser renderizada.
     */
    public function getMelhoresUsuarios($pagina = 0)
    {
        $limit = 100;
        $offset = 0;
        $retorno = array();
        $usuarios = $this->getTable('user','user')->getMelhoresUsuarios($limit, $offset);
        foreach($usuarios as $usuario){
            $user = new User();
            $user->exchangeArray($usuario);
            $user->nome = $usuario->nomeUsuario; 
            $user->pontoPermanente = PontoPermanente::getNewInstance()->fillInArray($usuario);
            $user->pontoMensal = $this->getModel('pontoMensal','user')->getPontoMensalUsuario($usuario->id_usuario);
            $user->totalImagens = $this->getModel('user','user')->getQuantidadeCapturadas($usuario->id_usuario);
            $user->reputacao = Reputacao::getNewInstance()->fillInArray($usuario);
            $retorno[] = $user;
        }
        
        return $retorno;
    }
    
    /**
     * Método que adiciona os pontos a um determinado usuário informado.
     * @param User $user usuário informado.
     */
    public function adicionarPonto($user)
    {
        $retorno = $this->getUsuario($user->id_usuario);
        if(!$retorno->id_usuario){
            $retorno->exchangeArray($user);
        }
        $retorno->pontos += constant('VALOR_PONTOS_'. strtoupper($retorno->tipo));
    	return $this->getTable('user')->save($retorno);
    }
}