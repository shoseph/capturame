<?php

namespace Application\Form;

use Zend\Validator\EmailAddress;

use User\Form\validators\BadWordsValidator;

use Zend\Validator\Date;

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

class AdicionarApoioForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'registrar';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'empresa',
            'attributes' => array('type'  => 'text', 'required' => 'required',),
        	'options' => array('label' => 'Empresa',),
        ));
        
        $this->add(array('name' => 'email',
            'attributes' => array('type'  => 'email', 'required' => 'required',),
            'options' => array('label' => 'Email',),
        ));
        
        $this->add(array('name' => 'apoio',
        		'attributes' => array('type'  => 'textarea', 'required' => 'required',),
        		'options' => array('label' => 'Apoio',),
        ));
        
        $this->add(array('name' => 'enviar',
            'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        
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
        
        // empresa
        $inputEmpresa = $this->_factory->createInput(array('name'=> 'empresa','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));

        // email
        $inputEmail = $this->_factory->createInput(array('name'=> 'email','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));
        
        // apoio
        $inputApoio = $this->_factory->createInput(array('name'=> 'apoio','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));

        // Padrões 
        $mensagemStringLengthPadrao = array(
            'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        	'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Empresa 
        $stringLengthEmpresa = new StringLength(array('encoding' => 'UTF-8','min' => 3,'max' => 40, 'messageTemplates' => $mensagemStringLengthPadrao));
        $validatorChain = new ValidatorChain();
        $inputEmpresa->setValidatorChain($validatorChain->addValidator($stringLengthEmpresa));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Email
        $stringLengthEmail = new StringLength(array('encoding' => 'UTF-8','min' => 5,'max' => 80, 'messageTemplates' => $mensagemStringLengthPadrao));
        $emailAdressEmail = new EmailAddress();
        $validatorChain = new ValidatorChain(array('messageTemplates' => array(
            'emailAddressInvalidFormat' => 'Email com formato inválido',
        )));
        $inputEmail->setValidatorChain($validatorChain->addValidator($stringLengthEmail));
        $inputEmail->setValidatorChain($validatorChain->addValidator($emailAdressEmail));
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        // Descrição 
        $stringLengthApoio = new StringLength(array('encoding' => 'UTF-8','min' => 5,'max' => 80, 'messageTemplates' => $mensagemStringLengthPadrao));
        $badwordsApoio = new BadWordsValidator();
        $validatorChain = new ValidatorChain();
        $inputApoio->setValidatorChain($validatorChain->addValidator($stringLengthApoio));
        $inputApoio->setValidatorChain($validatorChain->addValidator($badwordsApoio));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $this->_inputFilter->add($inputEmpresa);
        $this->_inputFilter->add($inputEmail);
        $this->_inputFilter->add($inputApoio);
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
