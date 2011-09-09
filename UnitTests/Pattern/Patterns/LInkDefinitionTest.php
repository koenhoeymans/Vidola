<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_LinkDefinitionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function aLinkDefinitionHasAName()
	{
		$linkDef = new \Vidola\Pattern\Patterns\LinkDefinition('name', 'url');
		$this->assertEquals('name', $linkDef->getName());
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionHasAUrl()
	{
		$linkDef = new \Vidola\Pattern\Patterns\LinkDefinition('name', 'url');
		$this->assertEquals('url', $linkDef->getUrl());
	}

	/**
	 * @test
	 */
	public function aLinkDefinitionOptionallyHasATitle()
	{
		$linkDef = new \Vidola\Pattern\Patterns\LinkDefinition('name', 'url', 'title');
		$this->assertEquals('title', $linkDef->getTitle());
	}
}