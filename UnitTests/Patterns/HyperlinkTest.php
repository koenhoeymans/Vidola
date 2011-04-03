<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_HyperlinkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->hyperlink = new \Vidola\Patterns\Hyperlink();
	}

	/**
	 * @test
	 */
	public function anUrlBetweenBracketsIsAutomaticallyLinked()
	{
		$text = "Please visit [http://example.com] for more information.";
		$html = "Please visit <a href=\"http://example.com\">http://example.com</a> for more information.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function anUrlBetweenBracketsCanHaveATitleBetweenSquareBracketsFollowingTheUrl()
	{
		$text = "Visit [http://example.com \"a title\"] for info.";
		$html = "Visit <a title=\"a title\" href=\"http://example.com\">http://example.com</a> for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function theUrlCanBePlacedElsewhereWhenLinkTextIsFollowedBySquareBracketedTitleAsReference()
	{
		$text = "Visit [examples website \"website of example.com\"] for info.\n\nparagraph\n\n[website of example.com]: http://example.com\n";
		$html = "Visit <a title=\"website of example.com\" href=\"http://example.com\">examples website</a> for info.\n\nparagraph\n";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function multipleReferencedLinksArePossible()
	{
		$this->markTestIncomplete();
	}
}