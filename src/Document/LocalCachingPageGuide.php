<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use AnyMark\AnyMark;
use Vidola\Util\TitleCreator;
use Vidola\Pattern\Patterns\TableOfContents;
use Vidola\Plugin\Observable;
use Vidola\Plugin\Pluggable;

/**
 * @package Vidola
 */
class LocalCachingPageGuide implements PageGuide, Observable
{
    use Pluggable;

    private $content = array();

    private $anyMark;

    private $titleCreator;

    private $toc;

    public function __construct(
        AnyMark $anyMark,
        TitleCreator $titleCreator,
        TableOfContents $toc
    ) {
        $this->anyMark = $anyMark;
        $this->titleCreator = $titleCreator;
        $this->toc = $toc;
    }

    /**
     * @see Vidola\Document.PageGuide::getParsedContent()
     */
    public function getParsedContent(Page $page, $dom = false)
    {
        # caching contents prevents from parsing more than once on next request
        # thus for \AnyMark\Pattern\Patterns\Header to assign another unique id
        $id = $page->getUrl();
        if (isset($this->content[$id])) {
            $parsedContent = $this->content[$id];
        } else {
            $rawContent = $page->getRawContent();
            $parsedContent = $this->anyMark->parse($rawContent);
            $this->content[$id] = $parsedContent;

            $this->notify(new \Vidola\Events\AfterParsing($parsedContent));
        }

        if ($dom) {
            return $parsedContent;
        }

        $savedToXml = $parsedContent->toString();
        $eventSavedToXml = new \Vidola\Events\SavedToXml($savedToXml);
        $this->notify($eventSavedToXml);

        return $eventSavedToXml->getXmlString();
    }

    /**
     * @see Vidola\Document.PageGuide::getTitle()
     */
    public function getTitle(Page $page)
    {
        return $this->titleCreator->createPageTitle(
            $page->getRawContent(),
            $page->getUrl()
        );
    }

    /**
     * @see Vidola\Document.PageGuide::getToc()
     */
    public function getToc(Page $page, $maxDepth = null)
    {
        return $this->toc->createToc(
            $this->getParsedContent($page, true),
            $maxDepth
        );
    }
}
