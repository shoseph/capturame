<?php
namespace User\Form;
use Extended\Form\CapturaForm;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class EsqueceuSenhaForm extends CapturaForm
{
    
    public function getFormName()
    {
        return 'esqueceu-senha';
    }
    
    public function createFormFiled()
    {
        $this->add(array('name' => 'email',
            'attributes' => array('type'  => 'text',),
       		'options' => array('label' => 'Email',),
        ));
        
        $this->add(array('name' => 'enviar',
        		'attributes' => array('type'  => 'submit','value' => 'Enviar','id' => 'submitbutton',),
        
        ));
    }
    
    public function createFilters()
    {
        $inputEmail = $this->_factory->createInput(array(
       		'name'     => 'email',
       		'required' => true,
            'filters'  => array(
            		array('name' => 'StripTags'),
            		array('name' => 'StringTrim'),
            )
        ));
        
        $regexItensValidos = new Regex(array('pattern' => '/^[\\w\.\\-\\_@]*$/'));
        $regexItensValidos->setMessages(array(
        	'regexNotMatch' => 'Itens válidos = a-z . - _'
        ));
        
        // Email ////////////////////////////////////////////////////////////////////////
        $stringLengthEmail = new StringLength(array(
            'encoding' => 'UTF-8','min' => 4,'max' => 80,
        ));
        $stringLengthEmail->setMessages(array(
        	'stringLengthTooLong' => 'Email com tamanho inválido.',
        	'stringLengthTooShort' => 'Necessita informar um email válido.'
        ));
        $validatorEmail = new ValidatorChain();
        $validatorEmail->addValidator($stringLengthEmail);
        $validatorEmail->addValidator($regexItensValidos);
        $inputEmail->setValidatorChain($validatorEmail);
        $this->_inputFilter->add($inputEmail);
        //////////////////////////////////////////////////////////////////////////////////
        
        $this->inputFilter = $this->_inputFilter;
    }
    
    
}
