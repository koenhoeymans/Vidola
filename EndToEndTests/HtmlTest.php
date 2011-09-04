<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_HtmlTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'HtmlTest.html'))
		{
			unlink($dir . 'HtmlTest.html');
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
		. DIRECTORY_SEPARATOR . 'HtmlTest.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
	
		// when
		\Vidola\Vidola::run();
	
		// then
		$this->assertEquals(
		file_get_contents(
		__DIR__
		. DIRECTORY_SEPARATOR . 'Support'
		. DIRECTORY_SEPARATOR . 'HtmlTest.html'
		),
		file_get_contents(
		$_SERVER['argv']['target.dir']
		. DIRECTORY_SEPARATOR . 'HtmlTest.html'
		)
		);
	}
}