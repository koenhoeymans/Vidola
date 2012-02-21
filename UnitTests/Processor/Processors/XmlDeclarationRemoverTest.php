<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_XmlDeclarationRemoverTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\XmlDeclarationRemover();
	}

	/**
	 * @test
	 */
	public function removesXmlDeclaration()
	{
		$text = "<?xml version=\"1.0\">\n<a>para\n \t\npara</a>";
		$result = "<a>para\n \t\npara</a>";
		$this->assertEquals($result, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function removesOnlyAtStart()
	{
		
	}
}