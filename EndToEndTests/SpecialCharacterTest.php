<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_SpecialCharacterTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'SpecialCharacterTest.html'))
		{
			unlink($dir . 'SpecialCharacterTest.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function HtmlIsAllowed()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SpecialCharacterTest.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';
	
		// when
		\Vidola\Vidola::run();
	
		// then
		$this->assertEquals(
		file_get_contents(
		__DIR__
		. DIRECTORY_SEPARATOR . 'Support'
		. DIRECTORY_SEPARATOR . 'SpecialCharacterTest.html'
		),
		file_get_contents(
		$_SERVER['argv']['target.dir']
		. DIRECTORY_SEPARATOR . 'SpecialCharacterTest.html'
		)
		);
	}
}