<?php

namespace Vidola;

/**
 * Sets up the necessary components before building the documents.
 */

class Vidola
{
	public static function run()
	{
		ini_set('pcre.backtrack_limit', 10000000);
		ini_set('pcre.recursion_limit', "524");
		if (ini_get('xdebug.max_nesting_level'))
		{
			ini_set('xdebug.max_nesting_level', 0);
		}

		// configuration
		// -----------------
		$config = new Config\CommandLineConfig($_SERVER['argv']);

		// setting up the object graph constructor
		// ---------------------------------------
		$fjor = \AnyMark\AnyMark::setup();
		$anyMark = $fjor->get('AnyMark\\AnyMark');
		$anyMark->setPatternsIni(__DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini');
		$fjor
			->given('AnyMark\\AnyMark')
			->thenUse($anyMark);
		$fjor
			->given('Vidola\\Util\\ContentRetriever')
			->thenUse('Vidola\\Util\\DocFileRetriever')
			->inSingletonScope();
		$fjor->setSingleton('Vidola\\Document\\MarkdownBasedDocumentation');
		$fjor
			->given('Vidola\\Util\\TitleCreator')
			->thenUse('Vidola\\Util\\HeaderBasedTitleCreator');
		$fjor
			->given('Vidola\\Document\\FilenameCreator')
			->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
		$fjor
			->given('Vidola\\Document\\DocumentationApiBuilder')
			->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
		$fjor
			->given('Vidola\\Document\\PageList')
			->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
		$fjor
			->given('Vidola\\Document\\Structure')
			->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
		$fjor
			->given('Vidola\\View\\TemplatableFileView')
			->thenUse('Vidola\\View\\StoredTemplatableFileView')
			->inSingletonScope();
		$fjor
			->given('Vidola\\Util\\TocGenerator')
			->thenUse('Vidola\\Util\\HtmlHeaderBasedTocGenerator')
			->inSingletonScope();

		// command line options
		// --------------------------------
		self::setCommandLineOptions($config, $fjor);

		// filling list of pages
		// ---------------------
		$pageListFiller = $fjor->get('Vidola\\Document\\PageListFiller');
		$pageListFiller->fill(
			$fjor->get('Vidola\\Document\\PageList'),
			self::getFile($config->get('source'))
		);

		// build the document(s)
		// ---------------------
		$documentCreationController = $fjor->get('Vidola\\Controller\\DocumentationCreationController');
		$documentCreationController->createDocumentation();

		// copy files
		// ----------
		if ($filesOrDirToCopy = $config->get('copy'))
		{
			$fileCopy = $fjor->get('Vidola\\Util\\FileCopy');
			$fileCopy->copy(
				dirname(self::getTemplate($config)),
				$config->get('target-dir'),
				$filesOrDirToCopy
			);
		}		
	}

	private static function setCommandLineOptions(
		\Vidola\Config\Config $config, \Fjor\Fjor $fjor
	) {
		// set type of internal url builder
		// --internal-links=
		// --------------------------------
		$urlBuilder = $config->get('internal-links') ?:
			'Vidola\\Util\\HtmlFileUrlBuilder';
		$fjor
			->given('Vidola\\Util\\InternalUrlBuilder')
			->thenUse($urlBuilder);

		// set the source directory or file
		// --source=
		// --------------------------------
		$docFileRetriever = $fjor->get('Vidola\\Util\\DocFileRetriever');
		if ($config->get('source') === null)
		{
			throw new \Exception('what is the source? --source=<source>');
		}
		$docFileRetriever->setSourceDir(self::getSourceDir($config->get('source')));

		// set the output directory
		// --target-dir=
		// ------------------------
		$view = $fjor->get('Vidola\\View\\StoredTemplatableFileView');
		if (!$config->get('target-dir'))
		{
			throw new \Exception('target directory not set: --target-dir=<dir>');
		}
		$view->setOutputDir($config->get('target-dir'));

		// set the template
		// --template=
		// ----------------
		$view->setTemplate(self::getTemplate($config));

		// set the file extension
		// @todo create command line option
		// ----------------------
		$view->setFileExtension('html');
	}

	private static function getTemplate(\Vidola\Config\Config $config)
	{
		return $config->get('template') ?:
			__DIR__
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
	}

	private static function getSourceDir($source)
	{
		if (!is_file($source))
		{
			throw new \Exception('Source is not a file.');
		}

		return dirname($source);
	}

	private static function getFile($source)
	{
		$fileParts = pathinfo($source);
		return $fileParts['filename'];
	}
}

?>