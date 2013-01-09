<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_SampleDocumentTest extends \Vidola\EndToEndTests\Support\Tidy
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'ParentDocumentSubfolderSubdocument.html'))
		{
			unlink($dir . 'ParentDocumentSubfolderSubdocument.html');
		}
		if (file_exists($dir . 'NextDocument.html'))
		{
			unlink($dir . 'NextDocument.html');
		}
		if (file_exists($dir . 'Subfolder' . DIRECTORY_SEPARATOR . 'Subdocument.html'))
		{
			unlink($dir . 'Subfolder' . DIRECTORY_SEPARATOR . 'Subdocument.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function createsFullDocument()
	{
		// given
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'SampleDocument'
			. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.txt';
		$targetDir = sys_get_temp_dir();

		// when
		exec("$bin $vidola --source={$source} --target.dir={$targetDir}");

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleDocument'
				. DIRECTORY_SEPARATOR . 'Html'
				. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir . DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			))
		);
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleDocument'
				. DIRECTORY_SEPARATOR . 'Html'
				. DIRECTORY_SEPARATOR . 'NextDocument.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir . DIRECTORY_SEPARATOR . 'NextDocument.html'
			))
		);
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SampleDocument'
				. DIRECTORY_SEPARATOR . 'Html'
				. DIRECTORY_SEPARATOR . 'Subfolder'
				. DIRECTORY_SEPARATOR . 'Subdocument.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir
				. DIRECTORY_SEPARATOR . 'Subfolder'
				. DIRECTORY_SEPARATOR . 'Subdocument.html'
			))
		);
	}
}