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
		$fjor = new \Fjor\Dsl\Dsl(new \Fjor\ObjectFactory\GenericObjectFactory());
		$fjor->given('Fjor\\Fjor')->thenUse($fjor);

		$fjor->setSingleton('Vidola\\Util\\PatternListFiller');
		$fjor->setSingleton('Vidola\\Pattern\\PatternList');
		$fjor->setSingleton('Vidola\\Patterns\\Pattern\\Header');
		$fjor->setSingleton('Vidola\\Processor\\Processors\\LinkDefinitionCollector');
		$fjor
			->given('Vidola\\Document\\DocumentApiBuilder')
			->thenUse('Vidola\\Document\\MarkdownBasedDocument')
			->inSingletonScope();
		$fjor
			->given('Vidola\\Document\\DocumentStructure')
			->thenUse('Vidola\\Document\\MarkdownBasedDocument')
			->inSingletonScope();
		$fjor
			->given('Vidola\\Util\\SubfileDetector')
			->thenUse('Vidola\\Pattern\\Patterns\\TableOfContents')
			->inSingletonScope();
		$fjor
			->given('Vidola\\TextReplacer\\TextReplacer')
			->thenUse('Vidola\\TextReplacer\\RecursiveReplacer\\RecursiveReplacer')
			->inSingletonScope();
		$fjor
			->given('Vidola\\View\\TemplatableFileView')
			->thenUse('Vidola\\View\\TemplatableHtmlFileView')
			->inSingletonScope();
		$fjor
			->given('Vidola\\Util\\ContentRetriever')
			->thenUse('Vidola\\Util\\DocFileRetriever')
			->inSingletonScope();
		// @todo move RecursiveReplacer to parser
		$fjor
			->given('Vidola\\Parser\\Parser')
			->thenUse('Vidola\\TextReplacer\\RecursiveReplacer\\RecursiveReplacer');

		// command line options
		// --------------------------------
		self::setCommandLineOptions($config, $fjor);

		// filling the pattern list with the patterns
		// ------------------------------------------
		$patternListFiller = $fjor->get('Vidola\\Util\\PatternListFiller');
		$patternList = $fjor->get('Vidola\\Pattern\\PatternList');
		$patternListFiller->fill($patternList, __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini');

		// adding processors
		// -----------------
		$htmlBuilder = $fjor->get('Vidola\\TextReplacer\\TextReplacer');
		$htmlBuilder->addPreTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\EmptyLineFixer')
		);
		$htmlBuilder->addPreTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\NewLineStandardizer')
		);
		$htmlBuilder->addPreTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\Detab')
		);
		$htmlBuilder->addPreTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\SpecialCharacterPreTextHandler')
		);
		$htmlBuilder->addPreTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\LinkDefinitionCollector')
		);
		$htmlBuilder->addPostDomProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\SpecialCharacterPostDomHandler')
		);
		$htmlBuilder->addPostTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\VidolaTagsToHtmlTags')
		);
		$htmlBuilder->addPostTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\SpecialCharacterPostTextHandler')
		);
		$htmlBuilder->addPostTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\XmlDeclarationRemover')
		);
		$htmlBuilder->addPostTextProcessor(
			$fjor->get('Vidola\\Processor\\Processors\\HtmlPrettifier')
		);

		// build the document(s)
		// ---------------------
		$documentCreationController = $fjor->get('Vidola\\Controller\\DocumentCreationController');
		$documentCreationController->createDocumentation();
	}

	private static function setCommandLineOptions(
		\Vidola\Config\Config $config, \Fjor\Fjor $fjor
	) {
		// set type of internal url builder
		// --internal.links=
		// --------------------------------
		$urlBuilder = $config->get('internal.links') ?:
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

		$fjor
			->given('Vidola\\Document\\MarkdownBasedDocument')
			->constructWith(array($config->get('source')));
		
		// set the output directory
		// --target.dir=
		// ------------------------
		$view = $fjor->get('Vidola\\View\\TemplatableFileView');
		if (!$config->get('target.dir'))
		{
			throw new \Exception('target directory not set: --target.dir=<dir>');
		}
		$view->setOutputDir($config->get('target.dir'));

		// set the template
		// --template=
		// ----------------
		$template = $config->get('template') ?:
			__DIR__
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
		$view->setTemplate($template);
	}

	private static function getSourceDir($source)
	{
		if (is_file($source))
		{
			$source = substr($source, 0, strrpos($source, DIRECTORY_SEPARATOR));
		}

		return realpath($source);
	}
}

?>