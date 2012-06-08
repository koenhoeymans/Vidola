<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_SampleTextTest extends \Vidola\EndToEndTests\Support\Tidy
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'SampleText.html'))
		{
			unlink($dir . 'SampleText.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function specifiedInputFileIsTransformedToHTML()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SampleText.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleText.html'
			)),
			$this->tidy(file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'SampleText.html'
			))
		);
	}
}