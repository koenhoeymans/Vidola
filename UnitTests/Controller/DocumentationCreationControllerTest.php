<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Controller_DocumentationCreationControllerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->documentationApiBuilder = $this->getMockBuilder('\\Vidola\\Document\\DocumentationApiBuilder')
										->disableOriginalConstructor()
										->getMock();
		$this->documentationStructure = $this->getMockBuilder('\\Vidola\\Document\\DocumentationStructure')
										->disableOriginalConstructor()
										->getMock();
		$this->view = $this->getMockBuilder('\\Vidola\\View\\TemplatableFileView')
							->disableOriginalConstructor()
							->getMock();
		$this->controller = new \Vidola\Controller\DocumentationCreationController(
			$this->documentationApiBuilder,
			$this->documentationStructure,
			$this->view
		);
	}

	/**
	 * @test
	 */
	public function tellsDocumentModelToBuildApi()
	{
		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentationStructure
				->expects($this->atLeastOnce())
				->method('getFileList')
				->will($this->returnValue(array('file')));

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function addsApiToView()
	{
		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentationStructure
				->expects($this->atLeastOnce())
				->method('getFileList')
				->will($this->returnValue(array('file')));
		$this->view
				->expects($this->once())
				->method('addApi');

		$this->controller->createDocumentation();
	}

	/**
   	 * @test
	 */
	public function tellsViewWhichFilenameToUse()
	{
		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentationStructure
				->expects($this->atLeastOnce())
				->method('getFileList')
				->will($this->returnValue(array('file')));
		$this->documentationStructure
				->expects($this->atLeastOnce())
				->method('createFilename')
				->will($this->returnValue('foo'));
		$this->view
				->expects($this->once())
				->method('setFilename')
				->with('foo');

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function tellsViewToRender()
	{
		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentationStructure
				->expects($this->atLeastOnce())
				->method('getFileList')
				->will($this->returnValue(array('file')));
		$this->view
				->expects($this->once())
				->method('render');

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function takesCareOfSubpages()
	{
		$this->documentationApiBuilder
				->expects($this->exactly(2))
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentationStructure
				->expects($this->at(0))
				->method('getFileList')
				->will($this->returnValue(array('file', 'subfile')));

		$this->controller->createDocumentation();
	}
}