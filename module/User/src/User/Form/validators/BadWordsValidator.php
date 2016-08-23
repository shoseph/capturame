<?php
namespace User\Form\validators;

use Zend\Validator\AbstractValidator;

class BadWordsValidator extends AbstractValidator
{
    
    const WITH_BADWORDS = 'badwords';
    
	protected $messageTemplates = array(
	    self::WITH_BADWORDS => "('%value%') são palavras impróprias para se dizer.",
    );

	/**
	 * Método que retorna qual o location do usuário
	 */
	private function getLocation()
	{
	    return 'pt-br';
	}
	
	/**
	 * Tentar Capturar as BadWords do site
	 */
	private function getBadWords()
	{
	    $badwords = require constant('ROOT') . 'module/User/config/badwords.php';
	    $badwordsLocation = $badwords[$this->getLocation()];
	    $regexBadwords = implode('|', $badwordsLocation);
	    return "/(?<badwords>{$regexBadwords})/";
	}
	
   	/**
	 * @param   mixed $value 
	 * @return  boolean 
	 * @throws  Zend_Valid_Exception If validation of $value is impossible      
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value)
	{
	    
	    if($value && !preg_match_all($this->getBadWords(), $value, $matches)){
    	    return true;
	    }
        $this->setValue(implode(',', $matches['badwords']));
        $this->error(self::WITH_BADWORDS);
        return false;
	    
	}

}
