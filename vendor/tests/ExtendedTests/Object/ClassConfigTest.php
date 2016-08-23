<?php
namespace ExtendedTests\Object;
use Extended\Object\ClassConfig;
use Zend\Mvc\Controller\AbstractActionController;


/**
 * ClassConfig test case.
 */
class ClassConfigTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
    }

    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
    }
    
    /**
     * Método que testa a execução de mais e uma chamada a classe
     * classConfig.
     */
    public function testGeralAction ()
    {
    	$class1 = 'Application\Controller\CaptchaController';
    	$class2 = 'Application\Controller\IndexController';
    
    	// Verificando se monta o objeto corremtamente
    	$this->assertAttributeNotEmpty('_module', ClassConfig::att(new $class1));
    	$this->assertAttributeNotEmpty('_name', ClassConfig::att(new $class1));
    	$this->assertAttributeNotEmpty('_classname', ClassConfig::att(new $class1));
    	$this->assertAttributeNotEmpty('_type', ClassConfig::att(new $class1));
    	
    	$nome2 = ClassConfig::att(new $class2)->name;
    	$this->assertEquals('Index', $nome2);
    	
    	$nome1 = ClassConfig::att(new $class1)->name;
    	$this->assertEquals('Captcha', $nome1);
    
    }

}

