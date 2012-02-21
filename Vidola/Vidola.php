<?php

namespace Vidola;

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
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\EmptyLineFixer')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\NewLineStandardizer')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPreHandler')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\LinkDefinitionCollector')
		);
		$htmlBuilder->addPostDomProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPostDomHandler')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\VidolaTagsToHtmlTags')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\SpecialCharacterPostHandler')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\XmlDeclarationRemover')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\HtmlPrettifier')
		);

		// set the source directory or file
		// --------------------------------
		$docFileRetriever = $ogc->getInstance('Vidola\\Util\\DocFileRetriever');
		$docFileRetriever->setSourceDir(self::getSourceDir($config->get('source')));

		// set the output directory
		// ------------------------
		$writer = $ogc->getInstance('Vidola\\Util\\Writer');
		$writer->setOutputDir(self::getOutputDir($config->get('target.dir')));
		$writer->setExtension('.html');

		// choose the template
		// -------------------
		$template = $config->get('template') ?:
						__DIR__
						. DIRECTORY_SEPARATOR . 'Templates'
						. DIRECTORY_SEPARATOR . 'Default'
						. DIRECTORY_SEPARATOR . 'Index.php';
		$templateBuilder = $ogc->getInstance('Vidola\\OutputBuilder\\OutputBuilder');
		$templateBuilder->setTemplate($template);

		// build the document(s)
		// ---------------------
		$documentBuilder = $ogc->getInstance('Vidola\\DocumentBuilder\\DocumentBuilder');
		$documentBuilder->build($config->get('source'));
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