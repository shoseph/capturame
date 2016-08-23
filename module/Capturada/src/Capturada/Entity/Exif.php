<?php

namespace Capturada\Entity;

use Extended\Table\CapturaObject;

class Exif extends CapturaObject
{

    /**
     * @name ='path'
     * @type='string'
     */
    protected $path;

    /**
     * @name ='marca'
     * @type='string'
     */
    protected $marca;
    
    /**
     * @name ='modelo'
     * @type='string'
     */
    protected $modelo;
    
    /**
     * @name ='data'
     * @type='string'
     */
    protected $data;
    
    /**
     * @name ='exposicao'
     * @type='string'
     */
    protected $exposicao;
    
    /**
     * @name ='abertura'
     * @type='string'
     */
    protected $abertura;
    
    /**
     * @name ='iso'
     * @type='string'
     */
    protected $iso;

    /**
     * @name ='distanciafocal'
     * @type='string'
     */
    protected $distanciafocal;
    
    /**
     * @name ='flash'
     * @type='string'
     */
    protected $flash;
    
    /**
     * @name ='software'
     * @type='string'
     */
    protected $software;
    
    
    /**
     * @name ='width'
     * @type='string'
     */
    protected $width;
    
    /**
     * @name ='height'
     * @type='string'
     */
    protected $height;
    
    /**
     * @name ='mime'
     * @type='string'
     */
    protected $mime;
    
    /**
     * Método que seta uma variável se ela existe no indice do array
     * @param Variavel $var variável do Exif
     * @param Array $array array de dados
     * @param String $key Chave requisitada
     */
    public function setIfExists(&$var, $array, $key)
    {
        $var = !array_key_exists($key, $array) ? null : $array[$key];
    }
    
    /**
     * Método que seta os parametros da um ExifObject
     * @param unknown_type $path
     */
    public function setParams($path)
    {
        $this->path = $path;
        $exif = exif_read_data($this->path, 0 , true);

        if(array_key_exists('COMPUTED', $exif)){
        	$this->setIfExists($this->abertura, $exif['COMPUTED'], 'ApertureFNumber');
        	$this->setIfExists($this->width, $exif['COMPUTED'], 'Width');
        	$this->setIfExists($this->height, $exif['COMPUTED'], 'Height');
        }
        
        if(array_key_exists('FILE', $exif)){
        	$this->setIfExists($this->mime, $exif['FILE'], 'MimeType');
        }
        
        if(array_key_exists('IFD0', $exif)){
            $this->setIfExists($this->marca, $exif['IFD0'], 'Make');
            $this->setIfExists($this->modelo, $exif['IFD0'], 'Model');
            $this->setIfExists($this->data, $exif['IFD0'], 'DateTime');
            $this->setIfExists($this->software, $exif['IFD0'], 'Software');
        }
        if(array_key_exists('EXIF', $exif)){
            $this->setIfExists($this->exposicao, $exif['EXIF'], 'ExposureTime');
            $this->setIfExists($this->iso, $exif['EXIF'], 'ISOSpeedRatings');
            $this->setIfExists($this->flash, $exif['EXIF'], 'Flash');
            
            $this->setIfExists($this->distanciafocal, $exif['EXIF'], 'FocalLength');
            if($this->distanciafocal){
                $df = explode('/', $this->distanciafocal);
                $this->distanciafocal = round($df[0] / $df[1],2) . ' mm';
            }
        }
    }
    
    /**
     * Método que retorna uma nova instancia obrigatoria
     * utilizado para se popular dados.
     */
    public static function factory($path)
    {
    	$classe = '\\' .get_called_class();
    	$obj = new $classe();
    	$obj->setParams($path);
    	return $obj;
    }        
    
}