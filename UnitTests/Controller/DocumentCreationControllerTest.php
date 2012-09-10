<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Controller_DocumentCreationControllerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->documentApiBuilder = $this->getMockBuilder('\\Vidola\\Document\\DocumentApiBuilder')
										->disableOriginalConstructor()
										->getMock();
		$this->documentStructure = $this->getMockBuilder('\\Vidola\\Document\\DocumentStructure')
										->disableOriginalConstructor()
										->getMock();
		$this->view = $this->getMockBuilder('\\Vidola\\View\\TemplatableFileView')
							->disableOriginalConstructor()
							->getMock();
		$this->controller = new \Vidola\Controller\DocumentCreationController(
			$this->documentApiBuilder,
			$this->documentStructure,
			$this->view
		);
	}

	/**
	 * @test
	 */
	public function tellsDocumentModelToBuildApi()
	{
		$this->documentApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentStructure
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
		$this->documentApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentStructure
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
		$this->documentApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentStructure
				->expects($this->atLeastOnce())
				->method('getFileList')
				->will($this->returnValue(array('file')));
		$this->documentStructure
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
		$this->documentApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentStructure
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
		$this->documentApiBuilder
				->expects($this->exactly(2))
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->documentStructure
				->expects($this->at(0))
				->method('getFileList')
				->will($this->returnValue(array('file', 'subfile')));

		$this->controller->createDocumentation();
	}
}