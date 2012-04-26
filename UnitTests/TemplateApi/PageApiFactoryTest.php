<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_TemplateApi_PageApiFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function providesPageApi()
	{
		$page = new \Vidola\Document\SimplePage();
		$PageApifactory = new \Vidola\TemplateApi\PageApiFactory();
		$pageApi = $PageApifactory->createWith($page);

		$this->assertEquals($pageApi, new \Vidola\TemplateApi\PageApi($page));
	}
}