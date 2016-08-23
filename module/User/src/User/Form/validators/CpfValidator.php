<?php
namespace User\Form\validators;

use Zend\Validator\AbstractValidator;

class CpfValidator extends AbstractValidator
{
    
    const COM_CPF = 'comcpfinvalido';
    const SEM_CPF = 'semcpfinvalido';
    const REGEX_NUM = '/^(\d){11}$/';
    const REGEX_ACENTO = '/^(\d){3}(\.\d{3}){2}-(\d){2}$/';
    
	protected $messageTemplates = array(
	    self::COM_CPF => "'%value%' não é um CPF válido.",
	    self::SEM_CPF => "CPF inválido.",
    );

   	/**
	 * @param   mixed $value 
	 * @return  boolean 
	 * @throws  Zend_Valid_Exception If validation of $value is impossible      
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value) {
	    
		// checks regexp, first and second validation Digit
         if ( preg_match(self::REGEX_NUM, $value) && $this->_checkDigitOne($value) 
                 && $this->_checkDigitTwo($value)){
            return true;
         }
         $this->setValue($value);
         $this->error(self::SEM_CPF);
         return false;
	}

    /**
     *
     * @param string $value
     * @return bool
     */
    private function _checkDigitOne($value)
    {
        $multipliers = array(10,9,8,7,6,5,4,3,2);
        return $this->_getDigit($value, $multipliers) == $value{9};
    }

    /**
     *
     * @param string $value
     * @return bool
     */

    private function _checkDigitTwo($value)
    {
        $multipliers = array(11,10,9,8,7,6,5,4,3,2);
        return $this->_getDigit($value, $multipliers) == $value{10};
    }

    /**
     *
     * @param string $value
     * @param array(int) $multipliers
     * @return int
     */
    private function _getDigit($value, $multipliers)
    {
        $sum = 0;
        foreach($multipliers as $key => $v){
            $sum += $value{$key} * $v;
        }
        $digit = $sum % 11;
        if ($digit < 2) {
            $digit = 0;
        }else{
            $digit = 11 - $digit;
        }
        return $digit;
    }

}
