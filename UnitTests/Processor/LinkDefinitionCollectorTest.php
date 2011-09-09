<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_LinkDefinitionCollectorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->collector = new \Vidola\Processor\LinkDefinitionCollector();
	}

	/**
	 * @test
	 */
	public function linkDefintionCollectorRemovesLinkDefinitionsFromText()
	{
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";
		$this->assertEquals(
			"\n",
			$this->collector->process($text)
		);
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionIsSquareBracketsWithDefinitionFollowedBySemicolonAndUrl()
	{
		// given
		$text = "\n[linkDefinition]: http://example.com \"title\"\n";

		// when
		$this->collector->process($text);

		// then
		$this->assertEquals(
			new \Vidola\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);

		$this->assertNull($this->collector->get('non existend definition'));
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionMustBePlacedOnItsOwnLine()
	{
		$text = "text [linkDefinition]: http://example.com";
		$this->assertEquals(
			"text [linkDefinition]: http://example.com",
			$this->collector->process($text)
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
		$this->collector->process($text);

		// then
		$this->assertEquals(
			new \Vidola\Pattern\Patterns\LinkDefinition(
				'linkDefinition', 'http://example.com', 'title'
			),
			$this->collector->get('linkDefinition')
		);
	}
}