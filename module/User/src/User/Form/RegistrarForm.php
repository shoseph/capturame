<?php

namespace User\Form;

use Extended\Form\CapturaForm;

use Zend\Form\Form;
use Zend\Form\Element\Captcha as Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Captcha\Dumb as Dumb;

use Zend\Validator\Identical;

use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use User\Form\validators\CpfValidator;
use Zend\Validator\ValidatorChain;

class RegistrarForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'registrar';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'nome',
            'attributes' => array('type'  => 'text', 'required' => 'required',),
        	'options' => array('label' => 'Nome',),
        ));
        $this->add(array('name' => 'login',
            'attributes' => array('type'  => 'text', 'required' => 'required',),
       		'options' => array('label' => 'Login',),
        ));
        $this->add(array('name' => 'senha',
            'attributes' => array('type'  => 'password', 'required' => 'required',),
       		'options' => array('label' => 'Senha',),
        ));
        $this->add(array('name' => 'verifysenha',
            'attributes' =>  array('type'  => 'password', 'required' => 'required',),
            'options' => array('label' => 'Verfificar Senha',),
        ));
        $this->add(array('name' => 'email',
            'attributes' => array('type'  => 'email', 'required' => 'required',),
            'options' => array('label' => 'Email',),
        ));
        $this->add(array('name' => 'cpf',
            'attributes' => array('type'  => 'text',),
            'options' => array('label' => 'CPF','encoding' => 'UTF-8','size' => 14,),
        ));
        $this->add(array('name' => 'id_facebook',
            'attributes' => array('type'  => 'hidden',),
            'options' => array(),
        ));
        $this->add(array('name' => 'enviar',
            'attributes' => array('type'  => 'submit','value' => 'Salvar','id' => 'submitbutton',),
        
        ));
        
//         $captchaImage = new CaptchaImage(  array(
//         		'font' => constant('DATA') . '/fonts/arial.ttf',
//                 'fontSize' => 15,
//         		'width' => 150,
//         		'height' => 40,
//         		'dotNoiseLevel' => 10,
//         		'lineNoiseLevel' => 2)
//         );
//         $captchaImage->setImgDir(constant('CAPTCHA'));
//         $captchaImage->setImgUrl('/gerar-captcha');
//         $captcha = new Captcha('captcha');
//         $captcha->setCaptcha($captchaImage);
        
        
//         $this->add($captcha);

    }
    public function createFilters()
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
        		'name'     => 'id_usuario',
        		'required' => true,
        		'filters'  => array(
        				array('name' => 'Int'),
        		),
        )));
        
        // nome
        $inputNome = $this->_factory->createInput(array(
    		'name'     => 'nome',
    		'required' => true,
    		'filters'  => array(
    				array('name' => 'StripTags'),
    				array('name' => 'StringTrim'),
    		)
        ));
        $stringLength = new StringLength();
        $stringLength->setOptions(array(
    		'encoding' => 'UTF-8',
    		'min'      => 7,
    		'max'      => 80,
        ));
        $stringLength->setMessages(array(
    		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
    		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        $validatorChain = new ValidatorChain();
        $inputNome->setValidatorChain($validatorChain->addValidator($stringLength));
        $this->_inputFilter->add($inputNome);

        // email
        $inputEmail = $this->_factory->createInput(array(
        		'name'     => 'email',
        		'required' => true,
        		'filters'  => array(
       				array('name' => 'StripTags'),
       				array('name' => 'StringTrim'),
        		)
        ));
        $stringLengthEmail = new StringLength();
        $stringLengthEmail->setOptions(array(
        		'encoding' => 'UTF-8',
        		'min'      => 5,
        		'max'      => 80,
        ));
        $stringLengthEmail->setMessages(array(
        		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        $validatorChain = new ValidatorChain();
        $inputEmail->setValidatorChain($validatorChain->addValidator($stringLengthEmail));
        $this->_inputFilter->add($inputEmail);
        
        // login
        $inputLogin = $this->_factory->createInput(array(
        		'name'     => 'login',
        		'required' => true,
        ));
        $regexItensValidos = new Regex(array('pattern' => '/^[\\w\.\\-]*$/'));
        $regexItensValidos->setMessages(array(
        		'regexNotMatch' => 'Itens válidos = a-z . - '
        ));
        $stringLengthLogin = new StringLength();
        $stringLengthLogin->setOptions(array(
        		'encoding' => 'UTF-8',
        		'min'      => 5,
        		'max'      => 40,
        ));
        $stringLengthLogin->setMessages(array(
        		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        $validatorChain = new ValidatorChain();
        $inputLogin->setValidatorChain($validatorChain->addValidator($regexItensValidos));
        $inputLogin->setValidatorChain($validatorChain->addValidator($stringLengthLogin));
        $this->_inputFilter->add($inputLogin);
        
        // senha
        $inputSenha = $this->_factory->createInput(array(
        		'name'     => 'senha',
        		'required' => true,
        ));
        $inputVerificarSenha = $this->_factory->createInput(array(
        		'name'     => 'verifysenha',
        		'required' => true,
        ));
        $stringLengthSenha = new StringLength();
        $stringLengthSenha->setOptions(array(
        		'encoding' => 'UTF-8',
        		'min'      => 6,
        		'max'      => 20,
        ));
        $stringLengthSenha->setMessages(array(
        		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        $validatorChain = new ValidatorChain();
        $senhaValidator = new Identical(array('token' => 'senha'));
        $senhaValidator->setMessages(array(
        		'notSame' => 'Senhas não conferem'
        ));
        $inputVerificarSenha->setValidatorChain($validatorChain->addValidator($senhaValidator));
        $inputVerificarSenha->setValidatorChain($validatorChain->addValidator($stringLengthSenha));
        $this->_inputFilter->add($inputVerificarSenha);
        
        // cpf
        $inputCpf = $this->_factory->createInput(array(
        		'name'     => 'cpf',
        		'required' => false,
        		'filters'  => array(
        				array('name' => 'Digits'),
        		),
        ));
        $validatorChain = new ValidatorChain();
        $inputCpf->setValidatorChain($validatorChain->addValidator(new CpfValidator()));
        $this->_inputFilter->add($inputCpf);
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
