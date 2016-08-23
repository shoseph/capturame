<?php
namespace User\Form;
use Extended\Form\CapturaForm;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class LoginForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'login-captura';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'login',
            'attributes' => array('type'  => 'text', 'id' => 'login', ),
       		'options' => array('label' => 'Login', ),
        ));
        
        $this->add(array('name' => 'senha',
            'attributes' => array('type'  => 'password', 'id' => 'senha', ),
       		'options' => array('label' => 'Senha',),
        ));
        
        $this->add(array('name' => 'enviar',
        		'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        
        ));
    }
    
    public function createFilters()
    {
        $inputLogin = $this->_factory->createInput(array(
       		'name'     => 'login',
       		'required' => true,
            'filters'  => array(
            		array('name' => 'StripTags'),
            		array('name' => 'StringTrim'),
            )
        ));
        
        $inputSenha = $this->_factory->createInput(array(
        	'name'     => 'senha',
        	'required' => true,
            'filters'  => array(
            		array('name' => 'StripTags'),
            		array('name' => 'StringTrim'),
            )
        ));
        
        
        $regexItensValidos = new Regex(array('pattern' => '/^[\\w\.\\-\\_@]*$/'));
        $regexItensValidos->setMessages(array(
        	'regexNotMatch' => 'Itens válidos = a-z . - _'
        ));
        
        // Login ////////////////////////////////////////////////////////////////////////
        $stringLengthLogin = new StringLength(array(
            'encoding' => 'UTF-8','min' => 4,'max' => 40,
        ));
        $stringLengthLogin->setMessages(array(
        	'stringLengthTooLong' => 'Login com tamanho inválido.',
        	'stringLengthTooShort' => 'Necessita informar um login válido.'
        ));
        $validatorLogin = new ValidatorChain();
        $validatorLogin->addValidator($stringLengthLogin);
        $validatorLogin->addValidator($regexItensValidos);
        $inputLogin->setValidatorChain($validatorLogin);
        $this->_inputFilter->add($inputLogin);
        //////////////////////////////////////////////////////////////////////////////////
        
        
        // senha //////////////////////////////////////////////////////////////////////////////
        $stringLengthSenha = new StringLength(array(
            'encoding' => 'UTF-8','min' => 6,'max' => 80,
        ));
        $stringLengthSenha->setMessages(array(
        	'stringLengthTooLong' => 'Senha com tamanho muito grande.',
        	'stringLengthTooShort' => 'Senha com tamanho pequeno, digite novamente.'
        ));
        $validadtorSenha = new ValidatorChain();
        $validadtorSenha->addValidator($stringLengthSenha);
        $inputSenha->setValidatorChain($validadtorSenha);
        $this->_inputFilter->add($inputSenha);
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
