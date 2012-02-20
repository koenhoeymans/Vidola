<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_SpecialCharacterPostHandlerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->handler = new \Vidola\Processor\Processors\SpecialCharacterPostHandler();
	}

	/**
	 * @test
	 */
	public function removesEncodedByDomSave()
	{
		$this->assertEquals('a &amp; b', $this->handler->process('a &amp;amp; b'));
	}
}