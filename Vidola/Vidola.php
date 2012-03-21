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
		$ogc = new \Vidola\Util\ObjectGraphConstructor();
		$ogc->willUse('Vidola\\TextReplacer\\RecursiveReplacer\\RecursiveReplacer');
		$ogc->willUse('Vidola\\OutputBuilder\\TemplateOutputBuilder');

		// filling the pattern list with the patterns
		// ------------------------------------------
		$patternListFiller = $ogc->getInstance('Vidola\\Util\\PatternListFiller');
		$patternList = $ogc->getInstance('Vidola\\Pattern\\PatternList');
		$patternListFiller->fill($patternList, __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini');

		// adding processors
		// -----------------
		$htmlBuilder = $ogc->getInstance('Vidola\\TextReplacer\\TextReplacer');
		$htmlBuilder->addPreTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\EmptyLineFixer')
		);
		$htmlBuilder->addPreTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\NewLineStandardizer')
		);
		$htmlBuilder->addPreTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\Detab')
		);
		$htmlBuilder->addPreTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPreTextHandler')
		);
		$htmlBuilder->addPreTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\LinkDefinitionCollector')
		);
		$htmlBuilder->addPostDomProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPostDomHandler')
		);
		$htmlBuilder->addPostTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\VidolaTagsToHtmlTags')
		);
		$htmlBuilder->addPostTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPostTextHandler')
		);
		$htmlBuilder->addPostTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\XmlDeclarationRemover')
		);
		$htmlBuilder->addPostTextProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\HtmlPrettifier')
		);

		// command line options
		// --------------------------------
		self::setCommandLineOptions($config, $ogc);

		// build the document(s)
		// ---------------------
		$documentBuilder = $ogc->getInstance('Vidola\\DocumentBuilder\\DocumentBuilder');
		$documentBuilder->build($config->get('source'));
	}

	private static function setCommandLineOptions(
		\Vidola\Config\Config $config, \Vidola\Util\ObjectGraphConstructor $ogc
	) {
		// set the source directory or file
		// --source=
		// --------------------------------
		$docFileRetriever = $ogc->getInstance('Vidola\\Util\\DocFileRetriever');
		if ($config->get('source') === null)
		{
			throw new \Exception('what is the source?');
		}
		$docFileRetriever->setSourceDir(self::getSourceDir($config->get('source')));
		
		// set the output directory
		// --target.dir=
		// ------------------------
		$writer = $ogc->getInstance('Vidola\\Util\\Writer');
		if ($config->get('target.dir') === null)
		{
			throw new \Exception('target directory not set: target.dir');
		}
		$writer->setOutputDir(self::getOutputDir($config->get('target.dir')));
		$writer->setExtension('.html');
		
		// set the template
		// --template=
		// -------------------
		$template = $config->get('template') ?:
			__DIR__
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
		$templateBuilder = $ogc->getInstance('Vidola\\OutputBuilder\\OutputBuilder');
		$templateBuilder->setTemplate($template);
	}

	private static function getSourceDir($source)
	{
		if (is_file($source))
		{
			$source = substr($source, 0, strrpos($source, DIRECTORY_SEPARATOR));
		}

		return realpath($source);
	}

	private static function getOutputDir($output)
	{
		return realpath($output);
	}
}

?>