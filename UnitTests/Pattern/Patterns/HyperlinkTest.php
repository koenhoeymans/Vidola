<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_HyperlinkTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\Vidola\\Processor\\Processors\\LinkDefinitionCollector'
		);
		$this->relativeUrlBuilder = $this->getMock(
			'\\Vidola\\Util\\RelativeUrlBuilder'
		);
		$this->hyperlink = new \Vidola\Pattern\Patterns\Hyperlink(
			$this->linkDefinitions, $this->relativeUrlBuilder
		);
	}

	/**
	 * @test
	 */
	public function anUrlHasAnchoTextInSquareBracketsFollowedByTheLinkInParentheses()
	{
		$text = "Visit [my site](http://example.com) for info.";
		$html = "Visit {{a href=\"http://example.com\"}}my site{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aLinkTitleCanBeSpecifiedAfterTheUrlInDoubleQuotes()
	{
		$text = "Visit [my site](http://example.com \"title\") for info.";
		$html = "Visit {{a href=\"http://example.com\" title=\"title\"}}my site{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aLinkTitleCanBeSpecifiedAfterTheUrlBetweenSingleQuotes()
	{
		$text = "Visit [my site](http://example.com 'title') for info.";
		$html = "Visit {{a href=\"http://example.com\" title=\"title\"}}my site{{/a}} for info.";
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
				new \Vidola\Pattern\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n";
		$html = "Visit {{a href=\"http://example.com\"}}my site{{/a}} for info.\n\n"
			. "paragraph\n\n";

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
				new \Vidola\Pattern\Patterns\LinkDefinition('1', 'http://example.com', 'title')));
		$text = "Visit [my site][1] for info.\n\n"
			. "paragraph\n\n";
		$html = "Visit {{a href=\"http://example.com\" title=\"title\"}}my site{{/a}} for info.\n\n"
			. "paragraph\n\n";

		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linkDefinitionMayBePlacedASpaceAfterAnchorText()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('1')
			->will($this->returnValue(
				new \Vidola\Pattern\Patterns\LinkDefinition('1', 'http://example.com')));
		$text = "Visit [my site] [1] for info.\n\n"
			. "paragraph\n\n";
		$html = "Visit {{a href=\"http://example.com\"}}my site{{/a}} for info.\n\n"
			. "paragraph\n\n";

		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linkDefinitionMayBeLeftBlankForUseOfAnchorTextAsLinkDefinition()
	{
		$this->linkDefinitions
			->expects($this->once())
			->method('get')->with('my site')
			->will($this->returnValue(
		new \Vidola\Pattern\Patterns\LinkDefinition('my site', 'http://example.com')));
		$text = "Visit [my site] [] for info.\n\n"
		. "paragraph\n\n";
		$html = "Visit {{a href=\"http://example.com\"}}my site{{/a}} for info.\n\n"
		. "paragraph\n\n";
	
		$this->assertEquals(
		$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function anchorTextCanContainATextLink()
	{
		$text = "Visit [site http://x.com](http://y.com \"title\") for info.";
		$html = "Visit {{a href=\"http://y.com\" title=\"title\"}}site http://x.com{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function squareBracketsInLinksAreOk()
	{
		$text = "Visit [my website](http://example.com?x=[y]&foo=[bar]) for info.";
		$html = "Visit {{a href=\"http://example.com?x=[y]&amp;foo=[bar]\"}}my website{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linksCanBeRelative()
	{
		$this->relativeUrlBuilder
			->expects($this->once())
			->method('buildUrl')->with('x')
			->will($this->returnValue('x.html'));
		$text = "See page [x](x) for info.";
		$html = "See page {{a href=\"x.html\"}}x{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linkIsRelativeIfItContainsOnlyAlphaNumForwardSlashesBeforeAnOptionalNumberSign()
	{
$this->markTestIncomplete();
// should be more like: if not pointing to (http://)xxx.yyy
		$this->relativeUrlBuilder
			->expects($this->once())
			->method('buildUrl')->with('x/6/f4#f')
			->will($this->returnValue('x.html'));
		$text = "See page [x/6/f4#f](x/6/f4#f) for info.";
		$html = "See page {{a href=\"x.html\"}}x/6/f4#f{{/a}} for info.";
		$this->assertEquals(
			$html, $this->hyperlink->replace($text)
		);
	}
}