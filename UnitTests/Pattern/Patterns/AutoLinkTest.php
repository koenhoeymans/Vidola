<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_AutoLinkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\AutoLink();
	}

	/**
	 * @test
	 */
	public function anEmailAddressIsLinkedWhenPlacedBetweenALesserThanAndGreaterThanSign()
	{
		$text = "Mail to <me@xmpl.com>.";
		$html = "Mail to {{a href=\"mailto:me@xmpl.com\"}}me@xmpl.com{{/a}}.";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function withoutAngledBracketsNoMailLinkIsCreated()
	{
		$text = "Mail to me@example.com, it's an email address link.";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function anUrlBetweenLesserThanAndreaterThanSignIsAutolinked()
	{
		$text = "Visit <http://example.com>.";
		$html = "Visit {{a href=\"http://example.com\"}}http://example.com{{/a}}.";
		$this->assertEquals($html, $this->pattern->replace($text));
	}
}