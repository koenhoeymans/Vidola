<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_MultiDocumentTest extends \Vidola\EndToEndTests\Support\Tidy
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'ParentDocument.html'))
		{
			unlink($dir . 'ParentDocument.html');
		}
		if (file_exists($dir . 'SubDocument.html'))
		{
			unlink($dir . 'SubDocument.html');
		}
		if (file_exists($dir . 'Subfolder/Subdocument.html'))
		{
			unlink($dir . 'Subfolder/Subdocument.html');
		}
		if (file_exists($dir . 'ParentDocumentSubfolderSubdocument.html'))
		{
			unlink($dir . 'ParentDocumentSubfolderSubdocument.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function multipleDocumentsAreConnectedThroughTableOfContents()
	{
		// given
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocument.txt';
		$targetDir = sys_get_temp_dir();
		$template = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		exec("$bin $vidola --source={$source} --target-dir={$targetDir} --template={$template}");

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'ParentDocument.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir . DIRECTORY_SEPARATOR . 'ParentDocument.html'
			))
		);

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SubDocument.html'
			)),
			$this->tidy(file_get_contents(
				$targetDir . DIRECTORY_SEPARATOR . 'SubDocument.html'
			))
		);
	}

	/**
	 * @test
	 */
	public function subDocumentCanExistInSubDirectory()
	{
		// given
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.txt';
		$targetDir = sys_get_temp_dir();
		$template = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		exec("$bin $vidola --source={$source} --target-dir={$targetDir} --template={$template}");

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
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

	/**
	 * @test
	 */
	public function linksToPreviousAndNextDocumentsAreAvailable()
	{
		// given
		// note: using default template
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocument.txt';
		$targetDir = sys_get_temp_dir();

		// when
		exec("$bin $vidola --source={$source} --target-dir={$targetDir}");

		// then
		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$targetDir . DIRECTORY_SEPARATOR . 'ParentDocument.html'
				),
				'next'
			))
		);

		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$targetDir . DIRECTORY_SEPARATOR . 'SubDocument.html'
				),
				'previous'
			))
		);
	}

	/**
	 * @test
	 */
	public function subdocumentLinksToParentDocument()
	{
		// given
		// note: using default template
		$bin = PHP_BINARY;
		$vidola = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Vidola'
			. DIRECTORY_SEPARATOR . 'RunVidola.php';
		$source = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.txt';
		$targetDir = sys_get_temp_dir();

		// when
		exec("$bin $vidola --source={$source} --target-dir={$targetDir}");

		// then
		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$targetDir
					. DIRECTORY_SEPARATOR . 'Subfolder'
					. DIRECTORY_SEPARATOR . 'Subdocument.html'
				),
				'../ParentDocumentSubfolderSubdocument.html'
			))
		);
	}
}