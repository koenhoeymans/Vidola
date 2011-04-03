<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_EmailTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->email = new \Vidola\Patterns\Email();
	}

	/**
	 * @test
	 */
	public function anEmailAddressIsLinkedWhenPlacedBetweenAngledBrackets()
	{
		$text = "Mail to <me@example.com>, it's an email address link.";
		$html = "Mail to <a href=\"mailto:me@example.com\">me@example.com</a>, it's an email address link.";
		$this->assertEquals(
			$html, $this->email->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function withoutAngledBracketsNoLinkIsCreated()
	{
		$text = "Mail to me@example.com, it's an email address link.";
		$html = "Mail to me@example.com, it's an email address link.";
		$this->assertEquals(
			$html, $this->email->replace($text)
		);
	}
}