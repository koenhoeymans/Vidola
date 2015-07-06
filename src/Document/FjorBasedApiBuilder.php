<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Fjor\Fjor;

/**
 * @package Vidola
 */
class FjorBasedApiBuilder implements DocumentationApiBuilder
{
    private $fjor;

    public function __construct(Fjor $fjor)
    {
        $this->fjor = $fjor;
    }

    /**
     * @see Vidola\Document.DocumentationApiBuilder::buildApi()
     */
    public function buildApi(Page $page)
    {
        $pageGuide = $this->fjor->get('Vidola\\Document\\PageGuide');
        $structure = $this->fjor->get('Vidola\\Document\\Structure');

        return new \Vidola\Document\MarkdownBasedDocumentationViewApi(
            $page, $pageGuide, $structure
        );
    }
}
