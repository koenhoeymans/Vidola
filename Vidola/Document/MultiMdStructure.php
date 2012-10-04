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
	public function createTocNode(\DomDocument $domDoc)
	{
		$headers = array();
		$xpath = new \DOMXPath($domDoc);
		// @todo should be html agnostic
		$headerNodes = $xpath->query('//h1|h2|h3|h4|h5|h6');
		foreach ($headerNodes as $headerNode)
		{
			$headers[] = array(
				'id' => $headerNode->getAttribute('id'),
				'level' => $headerNode->nodeName[1],
				'title' => $headerNode->nodeValue
			);
		}
		return $this->toc->buildToc($headers, null, $domDoc);
	}

	/**
	 * @see Vidola\Document.Structure::createLink()
	 */
	public function createLink($to, $relativeTo = null)
	{
		return $this->internalUrlBuilder->createRelativeLink($to, $relativeTo);
	}
}