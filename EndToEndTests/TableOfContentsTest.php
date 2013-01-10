<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_TableOfContentsTest extends \Vidola\EndToEndTests\Support\Tidy
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'TableOfContents.html'))
		{
			unlink($dir . 'TableOfContents.html');
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
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'TableOfContents.txt';
		$targetDir = sys_get_temp_dir();
		$template = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		exec("$bin $vidola --source={$source} --target-dir={$targetDir} --template={$template}");

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'TableOfContents.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir . DIRECTORY_SEPARATOR . 'TableOfContents.html'
			))
		);
	}
}