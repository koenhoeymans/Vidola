<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_TemplateApi_PageApiTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function providesContent()
	{
		$document = $this->getMockBuilder('\\Vidola\\Document\\Page')->getMock();
		$document
			->expects($this->any())
			->method('getContent')
			->will($this->returnValue('foo'));
		$api = new \Vidola\TemplateApi\PageApi($document);

		$this->assertEquals('foo', $api->content());
	}

	/**
	 * @test
	 */
	public function providesFilename()
	{
		$document = $this->getMockBuilder('\\Vidola\\Document\\Page')->getMock();
		$document
			->expects($this->any())
			->method('getFilename')
			->will($this->returnValue('foo'));
		$api = new \Vidola\TemplateApi\PageApi($document);
	
		$this->assertEquals('foo', $api->filename());
	}

	/**
	 * @test
	 */
	public function providesTitle()
	{
		$document = $this->getMockBuilder('\\Vidola\\Document\\Page')->getMock();
		$document
			->expects($this->any())
			->method('getTitle')
			->will($this->returnValue('foo'));
		$api = new \Vidola\TemplateApi\PageApi($document);
	
		$this->assertEquals('foo', $api->title());
	}

	/**
	 * @test
	 */
	public function providesNameOfNextPage()
	{
		$document = $this->getMockBuilder('\\Vidola\\Document\\Page')->getMock();
		$document
			->expects($this->any())
			->method('getNextPage')
			->will($this->returnValue('foo'));
		$api = new \Vidola\TemplateApi\PageApi($document);
	
		$this->assertEquals('foo', $api->nextPageName());
	}

	/**
	 * @test
	 */
	public function providesNameOfPreviousPage()
	{
		$document = $this->getMockBuilder('\\Vidola\\Document\\Page')->getMock();
		$document
			->expects($this->any())
			->method('getPreviousPage')
			->will($this->returnValue('foo'));
		$api = new \Vidola\TemplateApi\PageApi($document);
	
		$this->assertEquals('foo', $api->previousPageName());
	}
}