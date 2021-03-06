<?php

namespace Vidola\Document;

class LocalCachingPageGuideTest extends \PHPUnit_Framework_TestCase
{
    private function createDom($string)
    {
        $domDoc = new \DomDocument();
        $domDoc->loadXML($string);

        return $domDoc;
    }

    public function __construct()
    {
        $this->anyMark = $anyMark = $this->getMockBuilder('\\AnyMark\\AnyMark')
            ->disableOriginalConstructor()
            ->getMock();
        $this->titleCreator = $this->getMock('\\Vidola\\Util\\TitleCreator');
        $this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageGuide = new \Vidola\Document\LocalCachingPageGuide(
            $this->anyMark,
            $this->titleCreator,
            $this->toc
        );
    }

    /**
     * @test
     */
    public function providesParsedContentOfPageAsString()
    {
        $tree = new \ElementTree\ElementTree();
        $tree->append($tree->createElement('doc'));
        $page = new \Vidola\Document\Page('a_page', 'content');

        $this->anyMark
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with('content')
            ->will($this->returnValue($tree));

        $this->assertEquals(
            '<doc />',
            $this->pageGuide->getParsedContent($page)
        );
    }

    /**
     * @test
     */
    public function providesParsedContentAsDomDocument()
    {
        $tree = new \ElementTree\ElementTree();
        $tree->append($tree->createElement('doc'));
        $page = new \Vidola\Document\Page('a_page', 'content');

        $this->anyMark
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with('content')
            ->will($this->returnValue($tree));

        $this->assertEquals(
            $tree,
            $this->pageGuide->getParsedContent($page, true)
        );
    }

    /**
     * @test
     */
    public function createsPageTitle()
    {
        $this->titleCreator
            ->expects($this->atLeastOnce())
            ->method('createPageTitle')
            ->with('content', 'a_page')
            ->will($this->returnValue('title'));

        $this->assertEquals(
            'title',
            $this->pageGuide->getTitle(new \Vidola\Document\Page('a_page', 'content'))
        );
    }

    /**
     * @test
     */
    public function createsTableOfContents()
    {
        $this->anyMark
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with('content')
            ->will($this->returnValue(new \ElementTree\ElementTree()));

        $this->toc
            ->expects($this->atLeastOnce())
            ->method('createToc')
            ->with(new \ElementTree\ElementTree(), 1);

        $this->pageGuide->getToc(new \Vidola\Document\Page('a_page', 'content'), 1);
    }
}
