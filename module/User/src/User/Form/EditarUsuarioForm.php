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

class EditarUsuarioForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'editar-usuario';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'nome',
            'attributes' => array('type'  => 'text', 'required' => 'required',),
        	'options' => array('label' => 'Nome',),
        ));
        $this->add(array('name' => 'email',
            'attributes' => array('type'  => 'email', 'required' => 'required',),
            'options' => array('label' => 'Email',),
        ));
        $this->add(array('name' => 'telefone',
            'attributes' => array('type'  => 'telefone', 'required' => 'required',),
            'options' => array('label' => 'Telefone',),
        ));
//         $this->add(array('name' => 'cpf',
//             'attributes' => array('type'  => 'text',),
//             'options' => array('label' => 'CPF','encoding' => 'UTF-8','size' => 14,),
//         ));
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
        
        
        // telefone
        $inputTelefone = $this->_factory->createInput(array(
    		'name'     => 'telefone',
    		'required' => true,
    		'filters'  => array(
    				array('name' => 'StripTags'),
    				array('name' => 'StringTrim'),
    				array('name' => 'Digits'),
    		)
        ));
        $stringLength = new StringLength();
        $stringLength->setOptions(array(
    		'encoding' => 'UTF-8',
    		'min'      => 8,
    		'max'      => 20,
        ));
        $stringLength->setMessages(array(
    		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
    		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        $validatorChain = new ValidatorChain();
        $inputTelefone->setValidatorChain($validatorChain->addValidator($stringLength));
        $this->_inputFilter->add($inputTelefone);

        
        
       
//         // cpf
//         $inputCpf = $this->_factory->createInput(array(
//         		'name'     => 'cpf',
//         		'required' => false,
//         		'filters'  => array(
//         				array('name' => 'Digits'),
//         		),
//         ));
//         $validatorChain = new ValidatorChain();
//         $inputCpf->setValidatorChain($validatorChain->addValidator(new CpfValidator()));
//         $this->_inputFilter->add($inputCpf);
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
