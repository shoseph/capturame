<?php
namespace User\Form;
use Extended\Form\CapturaForm;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class ModificarSenhaForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'modificar-senha';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'senha',
            'attributes' => array('type'  => 'password',),
       		'options' => array('label' => 'Senha',),
        ));
        
        $this->add(array('name' => 'enviar',
        		'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        
        ));
    }
    
    public function createFilters()
    {
        $inputSenha = $this->_factory->createInput(array(
        	'name'     => 'senha',
        	'required' => true,
            'filters'  => array(
            		array('name' => 'StripTags'),
            		array('name' => 'StringTrim'),
            )
        ));
        
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
