<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_TableOfContentsTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->headerFinder =
			$this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents\\HeaderFinder')
					->disableOriginalConstructor()
					->getMock();
		$this->docFileRetriever =
			$this->getMockBuilder('\\Vidola\\Util\\ContentRetriever')
					->disableOriginalConstructor()
					->getMock();
		$this->internalUrlBuilder =
			$this->getMockBuilder('\\AnyMark\\Util\\InternalUrlBuilder')
					->disableOriginalConstructor()
					->getMock();
		$this->toc = new \Vidola\Pattern\Patterns\TableOfContents(
			$this->headerFinder,
			$this->docFileRetriever,
			$this->internalUrlBuilder
		);
	}

	public function getPattern()
	{
		return $this->toc;
	}

	/**
	 * @test
	 */
	public function createsLocalTocInComponentTreeFromMatch()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'))
			));

		$ul = new \AnyMark\ComponentTree\Element('ul');
		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a');
		$anchorText = $ul->createText('header');
		$a->append($anchorText);
		$li->append($a);
		$a->setAttribute('href', '#header');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function respectsLevelOfHeadersThroughSublists()
	{
		$text = "{table of contents}

header
----

subheader
=========

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'),
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$ul = new \AnyMark\ComponentTree\Element('ul');
		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a', 'header');
		$a->append($ul->createText('header'));
		$li->append($a);
		$a->setAttribute('href', '#header');

		$subUl = new \AnyMark\ComponentTree\Element('ul');
		$li->append($subUl);
		$li = $ul->createElement('li');
		$subUl->append($li);
		$a = $ul->createElement('a');
		$a->append($ul->createText('subheader'));
		$li->append($a);
		$a->setAttribute('href', '#subheader');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function createsNestedToc()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header1a', 'level' => 1, 'id' => 'header1a'),
				array('title' => 'header2a', 'level' => 2, 'id' => 'header2a'),
				array('title' => 'header2b', 'level' => 2, 'id' => 'header2b'),
				array('title' => 'header1b', 'level' => 1, 'id' => 'header1b')
			)));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li1 = $ul->createElement('li');
		$ul->append($li1);
		$a1 = $ul->createElement('a');
		$a1->append($ul->createText('header1a'));
		$li1->append($a1);
		$a1->setAttribute('href', '#header1a');

		$li2 = $ul->createElement('li');
		$ul->append($li2);
		$a2 = $ul->createElement('a');
		$a2->append($ul->createText('header1b'));
		$li2->append($a2);
		$a2->setAttribute('href', '#header1b');

		$subUl1 = $ul->createElement('ul');
		$li1->append($subUl1);

		$subLi1 = $ul->createElement('li');
		$subUl1->append($subLi1);
		$suba1 = $ul->createElement('a');
		$suba1->append($ul->createText('header2a'));
		$subLi1->append($suba1);
		$suba1->setAttribute('href', '#header2a');

		$subLi2 = $ul->createElement('li');
		$subUl1->append($subLi2);
		$suba2 = $ul->createElement('a');
		$suba2->append($ul->createText('header2b'));
		$subLi2->append($suba2);
		$suba2->setAttribute('href', '#header2b');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function tocIsLimitedToItsSectionDeterminedByHeaderLevel()
	{
		$text = 
"header
---

paragraph

{table of contents}

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$ul = new \AnyMark\ComponentTree\Element('ul');
		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a');
		$anchorText = $ul->createText('subheader');
		$a->append($anchorText);
		$li->append($a);
		$a->setAttribute('href', '#subheader');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function tocOfSectionStopsAtSectionWithHigherLevelHeader()
	{
		$text = 
"header
---

paragraph

{table of contents}

subheader
===

paragraph

other header
---

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader'),
				array('title' => 'other header', 'level' => 1, 'id' => 'other-header')
			)));

		$ul = new \AnyMark\ComponentTree\Element('ul');
		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a');
		$anchorText = $ul->createText('subheader');
		$a->append($anchorText);
		$li->append($a);
		$a->setAttribute('href', '#subheader');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function depthOptionLimitsDepthOfToc()
	{
		$text = 
"{table of contents}
	depth: 1

header
---

paragraph

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'),
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$ul = new \AnyMark\ComponentTree\Element('ul');
		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a');
		$anchorText = $ul->createText('header');
		$a->append($anchorText);
		$li->append($a);
		$a->setAttribute('href', '#header');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function addingFileNameIncludesHeadersFromThatFileAfterCurrentDocumentHeaders()
	{
		$text =
"{table of contents}

	Includedfile

header
----

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header')
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'included header', 'level' => 1, 'id' => 'included-header')
			)));
		$this->docFileRetriever
			->expects($this->any())
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"included header
----

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile.html'));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li1 = $ul->createElement('li');
		$ul->append($li1);
		$a1 = $ul->createElement('a');
		$a1->append($ul->createText('header'));
		$li1->append($a1);
		$a1->setAttribute('href', '#header');

		$li2 = $ul->createElement('li');
		$ul->append($li2);
		$a2 = $ul->createElement('a');
		$a2->append($ul->createText('included header'));
		$li2->append($a2);
		$a2->setAttribute('href', 'Includedfile.html#included-header');		

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function firstEncounteredHeaderInCurrentDocumentDeterminesHighestLevel()
	{
		$text =
"level1
===

{table of contents}

	Includedfile

level2
---

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level2', 'level' => 2, 'id' => 'level2')
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level3', 'level' => 3, 'id' => 'level3')
			)));
		$this->docFileRetriever
			->expects($this->any())
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"level3
+++

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile.html'));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li1 = $ul->createElement('li');
		$ul->append($li1);
		$a1 = $ul->createElement('a');
		$anchorText = $ul->createText('level2');
		$a1->append($anchorText);
		$li1->append($a1);
		$a1->setAttribute('href', '#level2');

		$subUl1 = $ul->createElement('ul');
		$li1->append($subUl1);

		$subLi1 = $ul->createElement('li');
		$subUl1->append($subLi1);
		$subA1 = $ul->createElement('a');
		$anchorText = $ul->createText('level3');
		$subA1->append($anchorText);
		$subLi1->append($subA1);
		$subA1->setAttribute('href', 'Includedfile.html#level3');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function moreThanOneFileCanBeSpecified()
	{
		$text =
"{table of contents}

	Includedfile1
	Includedfile2

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1a', 'level' => 1, 'id' => 'level1a')
			)));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1b', 'level' => 1, 'id' => 'level1b')
			)));
		$this->docFileRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('Includedfile1')
			->will($this->returnValue(
"level1a
+++

some text"
			));
		$this->docFileRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('Includedfile2')
			->will($this->returnValue(
"level1b
+++

some text"
			));

		$this->internalUrlBuilder
			->expects($this->at(0))
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile1.html'));
		$this->internalUrlBuilder
			->expects($this->at(1))
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile2.html'));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li1 = $ul->createElement('li');
		$ul->append($li1);
		$a1 = $ul->createElement('a');
		$a1->append($ul->createText('level1a'));
		$li1->append($a1);
		$a1->setAttribute('href', 'Includedfile1.html#level1a');

		$li2 = $ul->createElement('li');
		$ul->append($li2);
		$a2 = $ul->createElement('a');
		$a2->append($ul->createText('level1b'));
		$li2->append($a2);
		$a2->setAttribute('href', 'Includedfile2.html#level1b');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function usesTocOfIncludedFiles()
	{
		$text =
"{table of contents}

	Includedfile

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header')
			)));
		$this->docFileRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"{table of contents}

	Subincludedfile

paragraph"
			));
		$this->docFileRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('Subincludedfile')
			->will($this->returnValue(
"header
---

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Subincludedfile.html'));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li1 = $ul->createElement('li');
		$ul->append($li1);
		$a1 = $ul->createElement('a');
		$a1->append($ul->createText('header'));
		$li1->append($a1);
		$a1->setAttribute('href', 'Subincludedfile.html#header');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function addsLinkToHeaderUsingHeaderId()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'xyz'))
			));

		$ul = new \AnyMark\ComponentTree\Element('ul');

		$li = $ul->createElement('li');
		$ul->append($li);
		$a = $ul->createElement('a');
		$a->append($ul->createText('header'));
		$li->append($a);
		$a->setAttribute('href', '#xyz');

		$this->assertEquals($ul, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function buildsTocFromDocument()
	{
		$doc = new \AnyMark\ComponentTree\ComponentTree;
		$h1 = $doc->createElement('h1', 'header');
		$anchor1 = $doc->createText('header');
		$h1->append($anchor1);
		$doc->append($h1);
		$h1->setAttribute('id', 'header');
		$h2 = $doc->createElement('h2', 'header');
		$anchor2 = $doc->createText('header2');
		$h2->append($anchor2);
		$doc->append($h2);
		$h2->setAttribute('id', 'header2');

		$toc = new \AnyMark\ComponentTree\Element('ul');

		$li = $toc->createElement('li');
		$a = $toc->createElement('a');
		$text = $toc->createText('header');
		$toc->append($li);
		$li->append($a);
		$a->append($text);
		$a->setAttribute('href', '#header');

		$ul = $toc->createElement('ul');
		$li2 = $toc->createElement('li');
		$a = $toc->createElement('a');
		$text = $toc->createText('header2');
		$li->append($ul);
		$ul->append($li2);
		$li2->append($a);
		$a->append($text);
		$a->setAttribute('href', '#header2');

		$this->assertEquals(
			$toc, $this->toc->createTocNode($doc)
		);
	}

	/**
	 * @test
	 */
	public function tocFromDocumentCanHaveMaximumDepth()
	{
		$maxDepth = 1;

		$doc = new \AnyMark\ComponentTree\ComponentTree;
		$h1 = $doc->createElement('h1', 'header1');
		$anchor1 = $doc->createText('header1');
		$h1->append($anchor1);
		$doc->append($h1);
		$h1->setAttribute('id', 'header1');
		$h2 = $doc->createElement('h2');
		$anchor2 = $doc->createText('header2');
		$h2->append($anchor2);
		$doc->append($h2);
		$h2->setAttribute('id', 'header2');

		$toc = new \AnyMark\ComponentTree\Element('ul');
		$li = $toc->createElement('li');
		$a = $toc->createElement('a');
		$text = $toc->createText('header1');
		$toc->append($li);
		$li->append($a);
		$a->append($text);
		$a->setAttribute('href', '#header1');

		$this->assertEquals(
			$toc, $this->toc->createTocNode($doc, $maxDepth)
		);
	}

	/**
	 * @test
	 */
	public function aCustomPageTitleCanBeSpecified()
	{
		$text = "{table of contents}

	new title <page>

paragraph";

		$this->toc->getSubpages($text);

		$this->assertEquals('new title', $this->toc->getSpecifiedTitleForPage('page'));
	}
}