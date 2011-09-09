<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_CodeInlineTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->codeInline = new \Vidola\Pattern\Patterns\CodeInline();
	}

	/**
	 * @test
	 */
	public function transformsCodeBetweenBackticks()
	{
		$this->assertEquals(
			'Text with {{code}}code{{/code}} in between.',
			$this->codeInline->replace('Text with ´code´ in between.')
		);
	}
}