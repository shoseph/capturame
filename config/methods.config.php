<?php

class Captura{

    /**
     * Imprime o trace corretamente com o valor a ser impresso do $var
     * @name dump
     * @param array
     * @param boolean
     * @author jose rafael mendes barbosa <root@captura.me>
     */
    public static function dump($var, $exit = false, $shortcut = false, $minimal = false)
    {
    	ob_start();
    	var_dump($var);
    	$var = ob_get_clean();
    	$backtrace = debug_backtrace();
    	//$caminho = '';
    	
    	$html = array('<table border="0" bordercolor="#FFFFFF" width="90%" cellpadding="2" cellspacing="1">
                           <thead>
    	                       <tr>
                    	           <th style="text-align: left;">Caminho</th>
                    	           <th style="text-align: left;">Arquivo</th>
                    	       </tr>
    	                   </thead>
                    	   <tbody>');
    	$tamanho = count($backtrace);
    	foreach($backtrace as $index => $trace){
    
    	    if($shortcut && $index == 0 ){
    	        continue;
    	    }
    	    if($minimal && $index > $tamanho - 10){
    	        continue;
    	    }
    		$line = !array_key_exists('line', $trace) ? :  $trace['line'];
    		if (array_key_exists('file', $trace)){
    		    $name = $trace['file'];
    		} elseif(array_key_exists('class', $trace)){
    		    $name = $trace['class'];
    		} else {
    		    $name = $trace['function'];
    		}
    		$file = explode('/', $name);
    		$file = $file[count($file)-1];
  		    $html[] = "<tr><td>{$name}</td><td><b style='color:#3399FF'>{$file}::{$line}</b></td></tr>";
    
    	}
		$html[] = '</tbody></table>';
		$result = implode('', $html);
    	
    	echo "<pre style='font-size : 12px;'><b style='font-size : 16px;'>::Captura.Me Traceroute::</b>\n\r{$result}<br />{$var}</pre><hr />";
    	if($exit){
    	    exit();
    	}
    }
    
    /**
     * Método que retorna resumo de um texto.
     * @param unknown_type $string
     * @param unknown_type $chars
     * @return string
     */
    public static function resumo($string,$chars) {
    	if (strlen($string) > $chars) {
    		while (substr($string,$chars,1) <> ' ' && ($chars < strlen($string))){
    			$chars++;
    		};
    	};
    	return substr($string,0,$chars);
    }
    
    /**
     * Método que divide um nome em várias partes e coloca a primeira letra em maisuculo.
     * @param String $name um nome do usuário
     */
    public static function rulleSplitName($name)
    {
        $names = explode(' ',$name);
        foreach($names as $i => $n){
            $names[$i] = ucfirst($n);
        }
        return implode(' ', $names);
    }
    
    /**
     * Método que cria uma mascara.
     * @param String $mask Mascara que deve ser aplicada.
     * @param String $string valor que deve ser aplicado a mascara.
     */
    public static function mask($mask, $string)
    {
        for($i=0;$i<strlen($string);$i++) {
            $mask[strpos($mask,"#")] = $string[$i];
        }
        return $mask;
    }
    
    /**
     * Método que cria uma mascara para um telefone
     * @param String $name um nome do usuário
     */
    public static function maskPhone($telefone)
    {
        return \Captura::mask('(##) ####-####', $telefone);
    }
    
    /**
     * Método que retorna em que environment está o captura rodando.
     */
    public static function getEnvironment()
    {

        switch ($_SERVER['HTTP_HOST']){
            case 'captura.br': case 'teste.captura.me': 
                $environment = 'test'; 
                break;
                
            case 'captura.me': 
                $environment = 'prod'; 
                break;
            default: $environment = 'test';
        }
        
        return $environment;
    }
    
}


// atalhos para as funções mais utilizadas

function dump($var, $exit = false){
    \captura::dump($var, $exit, true, true);
}

function dumpt($var, $exit = false, $shortcut = false, $minimal = false){
    ob_start();
    var_dump($var);
    $var = ob_get_clean();
    $backtrace = debug_backtrace();
    //$caminho = '';
     
    $html = array("Caminho \t\t\t\t\t\t\t\t\t\t Arquivo\n");
    $tamanho = count($backtrace);
    foreach($backtrace as $index => $trace){
    
    	if($shortcut && $index == 0 ){
    		continue;
    	}
    	if($minimal && $index > $tamanho - 10){
    		continue;
    	}
    	$line = !array_key_exists('line', $trace) ? :  $trace['line'];
    	if (array_key_exists('file', $trace)){
    		$name = $trace['file'];
    	} elseif(array_key_exists('class', $trace)){
    		$name = $trace['class'];
    	} else {
    		$name = $trace['function'];
    	}
    	$file = explode('/', $name);
    	$file = $file[count($file)-1];
    	$html[] = "{$name} \t\t\t\t\t {$file}::{$line}\n";
    
    }
    $html[] = '';
    $result = implode('', $html);
     
    echo "::Captura.Me TERMINAL Traceroute::\n{$result}\n{$var}";
    if($exit){ exit(); }
}