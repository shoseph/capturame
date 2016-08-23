<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class PaginadorHelper extends CapturaViewHelper
{

    public function __invoke ($baseLink, $totalDePaginas, $paginaSelecionada)
    {
        // Necessita ser impar
        $tamanhoMinimo = 5;
        $adjacentes = floor($tamanhoMinimo / 2);
        $config = array(
            'link' => $baseLink, 
            'totalPaginas' => $totalDePaginas,
            'view' => $this->getView(),
        );
        if ((int) $paginaSelecionada > 1){
            $config['anterior'] = $paginaSelecionada - 1;
        }
        
        if ((int) $paginaSelecionada != (int) $totalDePaginas){
            $config['proxima'] = $paginaSelecionada + 1;
        }
        
        if ($totalDePaginas <= $tamanhoMinimo){
            
            $config['paginaInicial'] = 1;
            $config['paginaFinal'] = $tamanhoMinimo > $totalDePaginas ? $totalDePaginas : $tamanhoMinimo;
            $config['paginaSelecionada'] = $paginaSelecionada;
            $config['linkAnterior'] =  array_key_exists('anterior', $config) ? "{$config['link']}{$config['anterior']}" :  null;
            $config['linkProxima'] =  array_key_exists('proxima', $config) ? "{$config['link']}{$config['proxima']}" : null;
            $this->adicionarLinks($config);
            return $this->renderPartial('paginador', $config, 'Application', 'Index');
            
        } else {
            
            $intervaloInicial = $paginaSelecionada > 2 ? $paginaSelecionada - $adjacentes : 1;
            if ($paginaSelecionada > $totalDePaginas - $adjacentes){
                
                $intervaloFinal = $totalDePaginas;
                $adicional = $tamanhoMinimo - (($intervaloFinal - $intervaloInicial) + 1);
                $intervaloInicial = $intervaloInicial - $adicional;
            } else {
                $intervaloFinal = $paginaSelecionada > 2 ? $paginaSelecionada + $adjacentes : $tamanhoMinimo;
            }
            
            $config['paginaInicial'] = $intervaloInicial;
            $config['paginaFinal'] = $intervaloFinal;
            $config['paginaSelecionada'] = $paginaSelecionada;
            $config['linkAnterior'] =  array_key_exists('anterior', $config) ? "{$config['link']}{$config['anterior']}" :  null;
            $config['linkProxima'] =  array_key_exists('proxima', $config) ? "{$config['link']}{$config['proxima']}" : null;
            $this->adicionarLinks($config);
            return $this->renderPartial('paginador', $config, 'Application', 'Index');
        }
    }
    
    /**
     * Método que adiciona no javascript os links de anterior e proxima
     * @param unknown_type $config
     */
    private function adicionarLinks($config)
    {
        $config['linkAnterior'] ? $this->getViewHelper('headScript')->appendScript("linkAnterior = '{$config['linkAnterior']}';" )
                                : $this->getViewHelper('headScript')->appendScript("linkAnterior = null;" );
        
        $config['linkProxima']  ? $this->getViewHelper('headScript')->appendScript("linkProxima = '{$config['linkProxima']}';" )
                                : $this->getViewHelper('headScript')->appendScript("linkProxima = null;" );
        
    }
    
}