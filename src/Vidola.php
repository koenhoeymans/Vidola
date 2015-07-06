<?php

namespace Vidola;

/**
 * Sets up the necessary components before building the documents.
 */

class Vidola implements \Epa\Plugin
{
    public static function run()
    {
        ini_set('pcre.backtrack_limit', 10000000);
        ini_set('pcre.recursion_limit', "524");
        if (ini_get('xdebug.max_nesting_level')) {
            ini_set('xdebug.max_nesting_level', 0);
        }

        // configuration
        // -----------------
        $config = new Config\CommandLineConfig($_SERVER['argv']);

        // setting up the object graph constructor
        // ---------------------------------------
        $fjor = \Fjor\Fjor::defaultSetup();
        $anyMark = \AnyMark\AnyMark::setup($fjor);
        $anyMark->registerPlugin(new Vidola());
        $fjor
            ->given('Vidola\\Plugin\\Observable')
            ->andMethod('addObserver')
            ->addParam(array('Vidola\\Plugin\\EventDispatcher'));
        $fjor
            ->given('AnyMark\\AnyMark')
            ->thenUse($anyMark);
        $fjor
            ->given('Vidola\\Util\\ContentRetriever')
            ->thenUse('Vidola\\Util\\DocFileRetriever')
            ->inSingletonScope();
        $fjor
            ->given('Vidola\\Util\\TitleCreator')
            ->thenUse('Vidola\\Util\\HeaderBasedTitleCreator');
        $fjor
            ->given('Vidola\\Document\\DocumentationApiBuilder')
            ->thenUse('Vidola\\Document\\FjorBasedApiBuilder');
        $fjor
            ->given('Vidola\\Document\\PageList')
            ->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
        $fjor
            ->given('Vidola\\Document\\Structure')
            ->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
        $fjor
            ->given('Vidola\\Document\\FilenameCreator')
            ->thenUse('Vidola\\Document\\MarkdownBasedDocumentation');
        $fjor->setSingleton('Vidola\\Document\\MarkdownBasedDocumentation');
        $fjor
            ->given('Vidola\\View\\TemplatableFileView')
            ->thenUse('Vidola\\View\\StoredTemplatableFileView')
            ->inSingletonScope();
        $fjor
            ->given('Vidola\\Util\\TocGenerator')
            ->thenUse('Vidola\\Util\\HtmlHeaderBasedTocGenerator');
        $fjor
            ->given('Vidola\\Document\\PageGuide')
            ->thenUse('Vidola\\Document\\LocalCachingPageGuide');
        $fjor->setSingleton('Vidola\\Plugin\\EventDispatcher');
        $fjor->setSingleton('Vidola\\Pattern\\Patterns\\TableOfContents');

        // command line options
        // --------------------------------
        self::setOptions($config, $fjor);

        // filling list of pages
        // ---------------------
        $pageListFiller = $fjor->get('Vidola\\Document\\PageListFiller');
        $pageListFiller->fill(
            $fjor->get('Vidola\\Document\\PageList'),
            self::getFile($config->get('source'))
        );

        // build the document(s)
        // ---------------------
        $documentCreationController = $fjor->get('Vidola\\Controller\\DocumentationCreationController');
        $documentCreationController->createDocumentation();

        // copy files
        // ----------
        $fileCopyController = $fjor->get('Vidola\\Controller\\FileCopyController');
        $fileCopyController->copyFiles($config);
    }

    private static function setOptions(
        \Vidola\Config\Config $config, \Fjor\Fjor $fjor
    ) {
        // set type of internal url builder
        // --internal-links=
        // --------------------------------
        $urlBuilder = $config->get('internal-links') ?:
            'Vidola\\Util\\HtmlFileUrlBuilder';
        $fjor
            ->given('Vidola\\Util\\RelativeInternalUrlBuilder')
            ->thenUse($urlBuilder);
        $fjor
            ->given('AnyMark\\Util\\InternalUrlBuilder')
            ->thenUse($urlBuilder);

        // set the source directory or file
        // --source=
        // --------------------------------
        $docFileRetriever = $fjor->get('Vidola\\Util\\DocFileRetriever');
        if ($config->get('source') === null) {
            throw new \Exception('what is the source? --source=<source>');
        }
        $docFileRetriever->setSourceDir(self::getSourceDir($config->get('source')));

        // set the output directory
        // --target-dir=
        // ------------------------
        $view = $fjor->get('Vidola\\View\\StoredTemplatableFileView');
        if (!$config->getTargetDir()) {
            throw new \Exception('target directory not set: --target-dir=<dir>');
        }
        $view->setOutputDir($config->getTargetDir());

        // set the template
        // --template=
        // ----------------
        $view->setTemplate($config->getTemplate());

        // set the file extension
        // @todo create command line option
        // ----------------------
        $view->setFileExtension('html');

        // load plugins
        // ------------
        $plugins = $config->get('plugins') ?: array();
        foreach ($plugins as $plugin) {
            $fjor
                ->given('Vidola\\Plugin\\EventDispatcher')
                ->andMethod('registerPlugin')
                ->addParam(array($plugin));
        }
    }

    private static function getSourceDir($source)
    {
        if (!is_file($source)) {
            throw new \Exception('Source is not a file.');
        }

        return dirname($source);
    }

    private static function getFile($source)
    {
        $fileParts = pathinfo($source);

        return $fileParts['filename'];
    }

    public function register(\Epa\EventMapper $mapper)
    {
        $mapper->registerForEvent(
            'EditPatternConfigurationEvent',
            function (\AnyMark\PublicApi\EditPatternConfigurationEvent $event) {
                $this->addPatterns($event);
            }
        );
    }

    private function addPatterns(\AnyMark\PublicApi\EditPatternConfigurationEvent $config)
    {
        $config->setImplementation(
            'header', 'Vidola\\Pattern\\Patterns\\Header'
        );
        $config->setImplementation(
            'toc', 'Vidola\\Pattern\\Patterns\\TableOfContents'
        );
        $config
            ->add('toc')
            ->toParent('root')
            ->first();
    }
}
