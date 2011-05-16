<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_TableOfContentsTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'Toc.html'))
		{
			unlink($dir . 'Toc.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function createsLocalTableOfContents()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'TableOfContents.vi';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
		$_SERVER['argv']['target.name'] = 'TableOfContents.html';

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'TableOfContents.html'
			),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'TableOfContents.html'
			)
		);
	}
}