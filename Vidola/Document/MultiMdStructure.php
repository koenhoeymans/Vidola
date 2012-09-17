<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Pattern\Patterns\TableOfContents;
use Vidola\Util\SubfileDetector;
use Vidola\Util\InternalUrlBuilder;

/**
 * @package
 */
class MultiMdStructure implements Structure
{
	private $subfileDetector;

	private $toc;

	private $internalUrlBuilder;

	public function __construct(
		SubfileDetector $subfileDetector,
		TableOfContents $toc,
		InternalUrlBuilder $internalUrlBuilder
	) {
		$this->subfileDetector = $subfileDetector;
		$this->toc = $toc;
		$this->internalUrlBuilder = $internalUrlBuilder;
	}

	/**
	 * @todo Toc implements SubfileDetector => duplication of responsibilities
	 * @see Vidola\Document.Structure::getSubfiles()
	 */
	public function getSubfiles($file)
	{
		return $this->subfileDetector->getSubfiles($file);
	}

	/**
	 * @see Vidola\Document.Structure::createTocNode()
	 */
	public function createTocNode($text, \DomDocument $domDoc)
	{
		return $this->toc->createTocNode($text, $domDoc);
	}

	/**
	 * @see Vidola\Document.Structure::createLink()
	 */
	public function createLink($to, $relativeTo = null)
	{
		return $this->internalUrlBuilder->createLink($to, $relativeTo);
	}
}