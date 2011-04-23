<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_LinkDefinitionCollectorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->collector = new \Vidola\Patterns\LinkDefinitionCollector();
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionIsSquareBracketsWithDefinitionFollowedBySemicolonAndUrl()
	{
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";
		$this->assertEquals(
			"\n",
			$this->collector->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function linkDefintionCollectorRemovesLinkDefinitionsFromText()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";

		// when
		$this->collector->replace($text);

		// then
		$this->assertEquals(
			new \Vidola\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionMustBePlacedOnItsOwnLine()
	{
		$text = "text [linkDefinition]: http://example.com";
		$this->assertEquals(
			"text [linkDefinition]: http://example.com",
			$this->collector->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionCanBeIndented()
	{
		// given
		$text = "\n\t[linkDefinition]: http://example.com \"title\"\n";

		// when
		$this->collector->replace($text);

		// then
		$this->assertEquals(
			new \Vidola\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}
}