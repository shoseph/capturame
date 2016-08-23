<?php
namespace User\Form;
use Zend\Filter\StripTags;

use User\Form\validators\BadWordsValidator;

use Extended\Form\CapturaForm;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class AdicionarRevistaForm extends CapturaForm
{
    public function __construct(){
        
        parent::__construct();
        $this->setAttribute('enctype','multipart/form-data');
        
    }
    
    public function getFormName()
    {
        return 'adicionarRevista';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'titulo',
            'attributes' => array('type'  => 'text',),
       		'options' => array('label' => 'Título', ),
        ));
        
        $this->add(array('name' => 'descricao',
            'attributes' => array('type'  => 'textarea', 'id' => 'descricao',  'rows' => '10', ),
       		'options' => array('label' => 'Descrição',),
        ));
        
        $this->add(array('name' => 'arquivo',
            'attributes' => array('type'  => 'file', 'id' => 'arquivo' ),
       		'options' => array('label' => 'Revista',),
        ));
        
        $this->add(array('name' => 'enviar',
        	'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        ));
    }
    
    public function createFilters()
    {
        // Padrões
        $mensagemStringLengthPadrao = array(
    		'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
    		'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        
        // Titulo
        $inputTitulo = $this->_factory->createInput(array('name' => 'titulo','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        $validatorChain = new ValidatorChain();
        $inputTitulo->setValidatorChain($validatorChain->addValidator(new StringLength(
        		array('encoding' => 'UTF-8','min' => 2,'max' => 40, 'messageTemplates' => $mensagemStringLengthPadrao))
        ));
        $this->_inputFilter->add($inputTitulo);
        
        // Descrição
        $inputDescricao = $this->_factory->createInput(array('name' => 'descricao','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        $validatorChain = new ValidatorChain();
        $inputDescricao->setValidatorChain($validatorChain->addValidator(new StringLength(
        		array('encoding' => 'UTF-8','min' => 2,'max' => 200, 'messageTemplates' => $mensagemStringLengthPadrao))
        ));
        $this->_inputFilter->add($inputDescricao);
        
        // Arquivo Validadores        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
