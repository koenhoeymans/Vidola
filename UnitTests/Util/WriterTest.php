<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_WriterTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->writer = new \Vidola\Util\Writer();
	}

	/**
	 * @test
	 */
	public function targetCannotBeEmpty()
	{
		$this->setExpectedException('Exception');
		$this->writer->write('text', null);
	}
}