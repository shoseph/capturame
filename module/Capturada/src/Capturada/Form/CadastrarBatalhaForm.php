<?php

namespace Capturada\Form;

use Zend\Validator\Date;

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

class CadastrarBatalhaForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'cadastrar-batalha';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'titulo',
            'attributes' => array('type'  => 'text',  'required' => 'required',),
        	'options' => array('label' => 'Título',),
        ));
        
        $this->add(array('name' => 'descricao',
            'attributes' => array('type'  => 'text',  'required' => 'required',),
        	'options' => array('label' => 'Descrição',),
        ));

        $this->add(array('name' => 'dtInicio',
            'attributes' => array('type'  => 'date', 'required' => 'required',),
        	'options' => array('label' => 'Data de Início',),
        ));
        
        $this->add(array('name' => 'dtFim',
            'attributes' => array('type'  => 'date',  'required' => 'required',),
        	'options' => array('label' => 'Data de Fim',),
        ));
        
        $this->add(array('name' => 'enviar',
        		'attributes' => array('type'  => 'submit','value' => 'Salvar','id' => 'submitbutton',),
        
        ));

    }
    public function createFilters()
    {
        // título
        $inputTitulo = $this->_factory->createInput(array('name' => 'titulo','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        
        // descrição
        $inputDescricao = $this->_factory->createInput(array('name' => 'descricao','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        
        // dataInicio
        $inputDataInicio = $this->_factory->createInput(array('name'=> 'dtInicio','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));

        // dataFim
        $inputDataFim = $this->_factory->createInput(array('name'=> 'dtFim','required' => true,'filters'  => array(array('name' => 'StripTags'),array('name' => 'StringTrim'),)));
        
        // Padrões
        $mensagemStringLengthPadrao = array(
       		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
       		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        
        // Filtro título
        $stringLengthTitulo = new StringLength(array('encoding' => 'UTF-8','min' => 4,'max' => 60, 'messageTemplates' => $mensagemStringLengthPadrao));
        $badwordsNome = new BadWordsValidator();
        $validatorChain = new ValidatorChain();
        $inputTitulo->setValidatorChain($validatorChain->addValidator($stringLengthTitulo));
        $inputTitulo->setValidatorChain($validatorChain->addValidator($badwordsNome));
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        // Filtro descrição
        $stringLengthDescricao = new StringLength(array('encoding' => 'UTF-8','min' => 4,'max' => 200, 'messageTemplates' => $mensagemStringLengthPadrao));
        $badwordsNome = new BadWordsValidator();
        $validatorChain = new ValidatorChain();
        $inputDescricao->setValidatorChain($validatorChain->addValidator($stringLengthDescricao));
        $inputDescricao->setValidatorChain($validatorChain->addValidator($badwordsNome));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Data Inicio
        $dateDataInicio = new Date(array('format' => 'd/m/Y', 'messageTemplates' => array(
        		'dateInvalid' => 'Data está mau formatada',
        		'dateInvalidDate' => 'Data está mau formatada',
        		'dateFalseFormat' => 'Não confere com o formato dia/mês/ano',
        )));
        $validatorChain = new ValidatorChain();
        $inputDataInicio->setValidatorChain($validatorChain->addValidator($dateDataInicio));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Data Fim
        $dateDataFim = new Date(array('format' => 'd/m/Y', 'messageTemplates' => array(
        		'dateInvalid' => 'Data está mau formatada',
        		'dateInvalidDate' => 'Data está mau formatada',
        		'dateFalseFormat' => 'Não confere com o formato dia/mês/ano',
        )));
        $validatorChain = new ValidatorChain();
        $inputDataFim->setValidatorChain($validatorChain->addValidator($dateDataFim));
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        // Arquivo Validadores
        $this->_inputFilter->add($inputTitulo);
        $this->_inputFilter->add($inputDescricao);
        $this->_inputFilter->add($inputDataInicio);
        $this->_inputFilter->add($inputDataFim);
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
