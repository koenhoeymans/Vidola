<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_CodeInlineTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->codeInline = new \Vidola\Pattern\Patterns\CodeInline();
	}

	public function getPattern()
	{
		return $this->codeInline;
	}

	/**
	 * @test
	 */
	public function transformsCodeBetweenBackticks()
	{
		$text = 'Text with `code` in between.';
		$dom = new \DOMElement('code', 'code');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canStartAndEndWithMultipleBackticks()
	{
		$text = 'Text with ``code`` in between.';
		$dom = new \DOMElement('code', 'code');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function backtickCanBePlacedWithinMultipleBackticks()
	{
		$text = 'Text with ``co`de`` in between.';
		$dom = new \DOMElement('code', 'co`de');
		$this->assertCreatesDomFromText($dom, $text);
	}
}