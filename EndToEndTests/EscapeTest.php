<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_EscapeTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'EscapeTest.html'))
		{
			unlink($dir . 'EscapeTest.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 * 
	 * See http://git.michelf.com/mdtest/, test 'backslash escapes'.
	 * I've adapted the result of '\\>' which would produce invalid output
	 * as '\>'.
	 */
	public function escapingPreventsSpecialMeaning()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'EscapeTest.txt';
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
				. DIRECTORY_SEPARATOR . 'EscapeTest.html'
			),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'EscapeTest.html'
			)
		);
	}
}