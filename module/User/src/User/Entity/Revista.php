<?php

namespace User\Entity;

class Revista extends Conteudo
{
	
	/**
	 * Caminho da revista
	 */
	public function getCaminhoRevista()
	{
	    return constant('PUBLIC') . 'revista/' . date('Y') . '/';
	}
	
	/**
	 * Caminho da revista
	 */
	public function getCaminhoArquivo()
	{
	    return $this->getCaminhoRevista() . date('Y') . '-' . date('m') . '-revista-captura-me.pdf';;
	}
	
	/**
	 * Caminho da revista
	 */
	public function getCaminhoArquivoPublico()
	{
	    return '/revista/' . date('Y') . '/' . date('Y') . '-' . date('m') . '-revista-captura-me.pdf';;
	}
	
}