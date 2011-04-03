<?php

require_once('TestHelper.php');

class SampleTextTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->target = __DIR__
			. DIRECTORY_SEPARATOR . 'Tmp'
			. DIRECTORY_SEPARATOR . 'SampleText.html';
	}

	public function teardown()
	{
		unlink($this->target);
	}

	/**
	 * @test
	 */
	public function blankLineThenTextThenBlankLineIsParagraph()
	{
		// given
		$_SERVER['argv']['file'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SampleText.vi';
		$_SERVER['argv']['target'] = $this->target;

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleText.html'
			),
			file_get_contents($this->target)
		);
	}
}