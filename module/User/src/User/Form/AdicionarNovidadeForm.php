<?php
namespace User\Form;
use Zend\Filter\StripTags;

use User\Form\validators\BadWordsValidator;

use Extended\Form\CapturaForm;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class AdicionarNovidadeForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'adicionarNovidade';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'titulo',
            'attributes' => array('type'  => 'text',),
       		'options' => array('label' => 'Título', 'class' => 'tamanhoPadraoTitulo'),
        ));
        
        $this->add(array('name' => 'conteudo',
            'attributes' => array('type'  => 'textarea', 'id' => 'idEditor',  'cols' => '180', 'rows' => '100', ),
       		'options' => array('label' => 'Conteúdo',),
        ));
        
        $this->add(array('name' => 'enviar',
        	'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        ));
    }
    
    public function createFilters()
    {
        // nome
        $inputTitulo = $this->_factory->createInput(array('name' => 'titulo','required' => true,'filters'  => array(array('name' => 'StripTags'), array('name' => 'StringTrim'),)));
        $inputConteudo = $this->_factory->createInput(array('name' => 'conteudo','required' => true,'filters'  => array(
            array('name' => 'StripTags', 'options' => array(
                    'allowTags' => array('p','strong','big','em','strike','ol','li','ul','blockquote','h1','h2','h3','h4','h5','h6','div','span','tt','img', 'a'),
                    'allowAttribs' => array('style', 'href','alt','src','width','height')
            )), 
            array('name' => 'StringTrim')
        )));
        
        // Padrões
        $mensagemStringLengthPadrao = array(
        	'stringLengthTooLong' => 'Tamanho inválido, tamanho máximo = %max%',
        	'stringLengthTooShort' => 'Tamanho inválido, tamanho mínimo = %min% '
        );
        
        // Filtro Nome
        $validatorChain = new ValidatorChain();
        $inputTitulo->setValidatorChain($validatorChain->addValidator(new StringLength(
                array('encoding' => 'UTF-8','min' => 2,'max' => 40, 'messageTemplates' => $mensagemStringLengthPadrao))
        ));
        $inputTitulo->setValidatorChain($validatorChain->addValidator(new BadWordsValidator()));
        $this->_inputFilter->add($inputTitulo);
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Filtro Nome
        $validatorChain = new ValidatorChain();
        $inputConteudo->setValidatorChain($validatorChain->addValidator(new StringLength(
                array('encoding' => 'UTF-8','min' => 1,'max' => 1500, 'messageTemplates' => $mensagemStringLengthPadrao))
        ));
        $inputConteudo->setValidatorChain($validatorChain->addValidator(new BadWordsValidator()));
        $this->_inputFilter->add($inputConteudo);
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Arquivo Validadores        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
