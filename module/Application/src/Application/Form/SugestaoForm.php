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

class SugestaoForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'registrar';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'evento',
            'attributes' => array('type'  => 'text', 'required' => 'required',),
        	'options' => array('label' => 'Evento',),
        ));
        
        $this->add(array('name' => 'email',
            'attributes' => array('type'  => 'email', 'required' => 'required',),
            'options' => array('label' => 'Email',),
        ));
        
        $this->add(array('name' => 'data',
            'attributes' => array('type'  => 'date', 'id' => 'data', 'required' => 'required',),
            'options' => array('label' => 'Data',),
        ));
        
        $this->add(array('name' => 'hora',
            'attributes' => array('type'  => 'time', 'required' => 'required',),
            'options' => array('label' => 'Hora',),
        ));
        
        $this->add(array('name' => 'descricao',
        		'attributes' => array('type'  => 'textarea', 'required' => 'required',),
        		'options' => array('label' => 'Descrição',),
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
        
        // evento
        $inputEvento = $this->_factory->createInput(array('name'=> 'evento','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));

        // email
        $inputEmail = $this->_factory->createInput(array('name'=> 'email','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));
        
        // descricao
        $inputDescricao = $this->_factory->createInput(array('name'=> 'descricao','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));

        // data
        $inputData = $this->_factory->createInput(array('name'=> 'data','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));
        
        // hora
        $inputHora = $this->_factory->createInput(array('name'=> 'hora','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));
        
        // Padrões 
        $mensagemStringLengthPadrao = array(
            'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        	'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Evento 
        $stringLengthEvento = new StringLength(array('encoding' => 'UTF-8','min' => 3,'max' => 40, 'messageTemplates' => $mensagemStringLengthPadrao));
        $validatorChain = new ValidatorChain();
        $inputEvento->setValidatorChain($validatorChain->addValidator($stringLengthEvento));
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
        $stringLengthDescricao = new StringLength(array('encoding' => 'UTF-8','min' => 5,'max' => 80, 'messageTemplates' => $mensagemStringLengthPadrao));
        $badwordsDescricao = new BadWordsValidator();
        $validatorChain = new ValidatorChain();
        $inputDescricao->setValidatorChain($validatorChain->addValidator($stringLengthDescricao));
        $inputDescricao->setValidatorChain($validatorChain->addValidator($badwordsDescricao));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Data
        $dateData = new Date(array('format' => 'd/m/Y', 'messageTemplates' => array(
            'dateInvalid' => 'Data está mau formatada',
        	'dateInvalidDate' => 'Data está mau formatada',
        	'dateFalseFormat' => 'Não confere com o formato dia/mês/ano',
        )));
        $validatorChain = new ValidatorChain();
        $inputData->setValidatorChain($validatorChain->addValidator($dateData));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Hora
        $dateHora = new Date(array('format' => 'H:i', 'messageTemplates' => array(
            'dateInvalid' => 'Hora está mau formatada',
        	'dateInvalidDate' => 'Hora está mau formatada',
        	'dateFalseFormat' => 'Não confede com o formado hora:minuto',
        )));
        $validatorChain = new ValidatorChain();
        $inputHora->setValidatorChain($validatorChain->addValidator($dateHora));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $this->_inputFilter->add($inputEvento);
        $this->_inputFilter->add($inputEmail);
        $this->_inputFilter->add($inputDescricao);
        $this->_inputFilter->add($inputData);
        $this->_inputFilter->add($inputHora);
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
