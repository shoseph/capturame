<?php

namespace Capturada\Form;

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

class AdicionarCapturadaForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'adicionar-capturada';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'nome',
            'attributes' => array('type'  => 'text','required' => 'required',),
        	'options' => array('label' => 'Nome',),
        ));
        $this->add(array('name' => 'descricao',
            'attributes' => array('type'  => 'textarea','required' => 'required',),
       		'options' => array('label' => 'Descrição',),
        ));
        $this->add(array('name' => 'arquivo',
            'attributes' => array('type'  => 'file', 'required' => 'required', 'size' => '2'),
       		'options' => array('label' => 'Capturada', ),
        ));
        $this->add(array('name' => 'enviar',
        	'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        
        ));

    }
    public function createFilters()
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
        		'name'     => 'id_capturada',
        		'required' => true,
        		'filters'  => array(
        				array('name' => 'Int'),
        		),
        )));
        
        // nome
        $inputNome = $this->_factory->createInput(array(
    		'name'     => 'nome',
    		'required' => true,
    		'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)
        ));
        
        // descrição
        $inputDescricao = $this->_factory->createInput(array(
            'name'     => 'descricao',
            'required' => true,
            'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)
        ));
        
        // Arquivo
        $inputArquivo = $this->_factory->createInput(array(
       		'name'     => 'arquivo',
            'required' => true,
        ));
        
        // Filtro Tamanho
        $stringLengthNome = new StringLength();
        $stringLengthNome->setOptions(array(
    		'encoding' => 'UTF-8',
    		'min'      => 1,
    		'max'      => 40,
        ));
        $stringLengthNome->setMessages(array(
    		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
    		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        
        // nome validadores
        $validatorNome = new ValidatorChain();
        $inputNome->setValidatorChain($validatorNome->addValidator($stringLengthNome));
        $this->_inputFilter->add($inputNome);
        
        
        $stringLengthDescricao = new StringLength();
        $stringLengthDescricao->setOptions(array(
        		'encoding' => 'UTF-8',
        		'min'      => 1,
        		'max'      => 400,
        ));
        $stringLengthDescricao->setMessages(array(
        		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        ));
        // Descrição validadores
        $validatorDescricao = new ValidatorChain();
        $inputDescricao->setValidatorChain($validatorDescricao->addValidator($stringLengthDescricao));
        $this->_inputFilter->add($inputDescricao);
        
        // Arquivo Validadores
        $this->_inputFilter->add($inputArquivo);
        
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
