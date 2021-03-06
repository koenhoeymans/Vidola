<?php

/**
 * @package AnyMark
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Util\ContentRetriever;
use Vidola\Pattern\Patterns\TableOfContents\HeaderFinder;
use AnyMark\Util\InternalUrlBuilder;
use AnyMark\Pattern\Pattern;
use ElementTree\Element;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class TableOfContents extends Pattern
{
    private $headerFinder;

    private $contentRetriever;

    private $internalUrlBuilder;

    /**
     * A list of custom page titles as specified in the toc.
     *
     * @var array array($page=>$title)
     */
    private $customPageTitles = array();

    public function __construct(
        HeaderFinder $headerFinder,
        ContentRetriever $contentRetriever,
        InternalUrlBuilder $internalUrlBuilder
    ) {
        $this->headerFinder = $headerFinder;
        $this->contentRetriever = $contentRetriever;
        $this->internalUrlBuilder = $internalUrlBuilder;
    }

    /**
     * @see AnyMark\Pattern.Pattern::getRegex()
     */
    public function getRegex()
    {
        return
            '@
			(?<=\n\n|\n^|^)
 			{table\ of\ contents}
 			(?<options>
				(
				\n
					((\t+|[ ]{4,}).+)*
				)?
			)
			(?<pages>
				(
				\n
					(\n(\t+|[ ]{4,}).+)+
				)?
			)
			(?=(?<text>\n\n[\S\s]*|$))
			@x';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) {
        return $this->buildReplacement($match, $parent);
    }

    public function getSubpages($text)
    {
        $pageList = array();
        preg_match_all($this->getRegex(), $text, $matches, PREG_PATTERN_ORDER);
        foreach ($matches['pages'] as $pages) {
            $matches = $this->getSubpagesFromAnyMarkText($pages);
            $pageList = array_merge($pageList, $matches);
        }

        return $pageList;
    }

    /**
     * If in the toc there was a title specified to use instead of the first header,
     * this will find it.
     *
     * @param string $page
     */
    public function getSpecifiedTitleForPage($page)
    {
        if (isset($this->customPageTitles[$page])) {
            return $this->customPageTitles[$page];
        }

        return;
    }

    private function buildReplacement(array $regexmatch)
    {
        $options = $this->getOptions($regexmatch['options']);
        $maxDepth = isset($options['depth']) ? $options['depth'] : null;

        $fileList = $this->recursivelyGetFilesToInclude($regexmatch['pages']);
        $textAfterToc = $regexmatch['text'];
        $headerList = $this->getListOfHeaders($textAfterToc, $fileList);

        return $this->buildToc($headerList, $maxDepth);
    }

    private function recursivelyGetFilesToInclude($regexPartWithListOfFiles)
    {
        $fileList = array();

        $namesFromCurrentList = $this->getSubpagesFromAnyMarkText($regexPartWithListOfFiles);

        foreach ($namesFromCurrentList as $fileToInclude) {
            $textOfFile = $this->contentRetriever->retrieve($fileToInclude);
            $fileList[$fileToInclude] = $textOfFile;

            preg_match_all(
                $this->getRegex(),
                $textOfFile,
                $tocBlocks,
                PREG_SET_ORDER
            );

            foreach ($tocBlocks as $toc) {
                $subFileList = $this->recursivelyGetFilesToInclude($toc['pages']);
                $fileList = array_merge($fileList, $subFileList);
            }
        }

        return $fileList;
    }

    private function getListOfHeaders($textAfterToc, $fileList)
    {
        $headers = $this->headerFinder->getHeadersSequentially($textAfterToc);

        foreach ($fileList as $fileName => $contents) {
            $subTextHeaders = $this->headerFinder->getHeadersSequentially($contents);
            foreach ($subTextHeaders as $subTextHeader) {
                $subTextHeader['file'] = ucfirst($fileName);
                $headers = array_merge(
                    $headers,
                    array($subTextHeader)
                );
            }
        }

        return $headers;
    }

    private function getSubpagesFromAnyMarkText($text)
    {
        $inclusionList = array();

        $lines = explode("\n", $text);
        foreach ($lines as $include) {
            if ($include !== '') {
                preg_match(
                    "@^(?<page_or_title>.+?)([ ]\<(?<page>.+)\>)?$@",
                    $include,
                    $matches
                );
                $page_or_title = trim($matches['page_or_title']);
                if (isset($matches['page'])) {
                    $include = trim($matches['page']);
                    $this->customPageTitles[$include] = $page_or_title;
                } else {
                    $include = $page_or_title;
                }

                $inclusionList[] = $include;
            }
        }

        return $inclusionList;
    }

    /**
     * Create a table of contents from a ComponentTree.
     *
     * @param  ComponentTree                   $componentTree
     * @param  string                          $maxDepth
     * @return \ElementTree\ElementTreeElement
     */
    public function createToc(ElementTree $elementTree, $maxDepth = null)
    {
        $headers = array();

        $query = $elementTree->createQuery($elementTree);
        $elements = $query->find($query->allElements($query->lOr(
            $query->withName('h1'),
            $query->withName('h2'),
            $query->withName('h1'),
            $query->withName('h4'),
            $query->withName('h5'),
            $query->withName('h5')
        )));
        foreach ($elements as $element) {
            $headers[] = array(
                'id' => $element->getAttributeValue('id'),
                'level' => $element->getName(),
                'title' => $element->getChildren()[0]->getValue(),
            );
        }

        return $this->buildToc($headers, $maxDepth);
    }

    private function buildToc(array $headers, $maxDepth = null)
    {
        if (empty($headers)) {
            return;
        }

        static $depth;

        if (!isset($depth)) {
            $depth = 1;
        }
        if ($maxDepth) {
            if ($depth > $maxDepth) {
                return;
            }
        }

        $ul = $this->createElement('ul');

        $listLevel = null;

        foreach ($headers as $key => $header) {
            unset($headers[$key]);

            $level = $header['level'];
            $title = $header['title'];
            $ref = $header['id'];
            $file = isset($header['file']) ?
                $this->internalUrlBuilder->urlTo($header['file']) :
                '';

            if (!$listLevel) {
                $listLevel = $level;
            } elseif ($level < $listLevel) {
                break;
            } elseif ($level > $listLevel) {
                continue;
            }

            $li = $this->createElement('li');
            $ul->append($li);
            $a = $this->createElement('a');
            $a->append($this->createText($title));
            $li->append($a);
            $a->setAttribute('href', $file.'#'.$ref);

            if (isset($headers[$key+1])) {
                if ($headers[$key+1]['level'] > $level) {
                    $depth++;
                    $subUl = $this->buildToc($headers, $maxDepth, $li);
                    if ($subUl) {
                        $li->append($subUl);
                    }
                    $depth--;
                }
            }
        }

        return $ul;
    }

    private function getOptions($text)
    {
        $options = array();

        preg_match_all("#($|\n)(\t| )*(.+?)(?=\n|$)#", $text, $matches);
        foreach ($matches[3] as $line) {
            $option = explode(':', $line);
            $options[$option[0]] = trim($option[1]);
        }

        return $options;
    }
}
