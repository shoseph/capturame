<?php

namespace Capturada\Form;

use User\Form\validators\BadWordsValidator;

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

class AdicionarTagForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'adicionar-tag';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'nome',
            'attributes' => array('type'  => 'text','required' => 'required',),
        	'options' => array('label' => 'Nome',),
        ));
        $this->add(array('name' => 'enviar',
        		'attributes' => array('type'  => 'submit','value' => 'Salvar','id' => 'submitbutton',),
        
        ));

    }
    public function createFilters()
    {
        // nome
        $inputNome = $this->_factory->createInput(array('name'     => 'nome','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        
        // Padrões
        $mensagemStringLengthPadrao = array(
        		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        
        // Filtro Nome
        $stringLengthNome = new StringLength(array('encoding' => 'UTF-8','min' => 2,'max' => 60, 'messageTemplates' => $mensagemStringLengthPadrao));
        $badwordsNome = new BadWordsValidator();
        $validatorChain = new ValidatorChain();
        $inputNome->setValidatorChain($validatorChain->addValidator($stringLengthNome));
        $inputNome->setValidatorChain($validatorChain->addValidator($badwordsNome));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Arquivo Validadores
        $this->_inputFilter->add($inputNome);
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
