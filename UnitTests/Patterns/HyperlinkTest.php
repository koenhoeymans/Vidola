<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_HyperlinkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\Vidola\\Patterns\\LinkDefinitionCollector'
		);
		$this->hyperlink = new \Vidola\Patterns\Hyperlink($this->linkDefinitions);
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
	public function anchorTextIsBetweenBracketsFollowedByUrl()
	{
		$text = "Visit [my website][http://example.com] for info.";
		$html = "Visit <a href=\"http://example.com\">my website</a> for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function anchorTextIsBetweenBracketsFollowedByUrlBetweenBracketsAndOptionallyTitle()
	{
		$text = "Visit [my website][http://example.com \"a title\"] for info.";
		$html = "Visit <a title=\"a title\" href=\"http://example.com\">my website</a> for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}


	/**
	 * @test
	 */
	public function theUrlCanBePlacedElsewhereWhenLinkTextIsFollowedBySquareBracketedTitleAsReference()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('1')
			->will($this->returnValue(
				new \Vidola\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";
		$html = "Visit <a href=\"http://example.com\">my site</a> for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";

		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function titleTextCanComeFromLinkDefinition()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('1')
			->will($this->returnValue(
				new \Vidola\Patterns\LinkDefinition('1', 'http://example.com', 'title')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";
		$html = "Visit <a title=\"title\" href=\"http://example.com\">my site</a> for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";

		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linkDefinitionMayBePlacedSpaceAfterAnchorText()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('1')
			->will($this->returnValue(
				new \Vidola\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site] [1] for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";
		$html = "Visit <a href=\"http://example.com\">my site</a> for info.\n\n"
			. "paragraph\n\n"
			. "[1]: http://example.com\n";

		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function anchorTextCanContainLink()
	{
		$text = "Visit [site http://x.com][http://y.com \"title\"] for info.";
		$html = "Visit <a title=\"title\" href=\"http://y.com\">site http://x.com</a> for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function textBetweenBracketsIsNotMistakenForLink()
	{
		$text = "Not a [link] pattern, nor is [\"this\"] a link.";
		$html = "Not a [link] pattern, nor is [\"this\"] a link.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}
}