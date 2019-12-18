<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use RZ\CommonMark\Ext\Footnote\Event\AnonymousFootnotesListener;
use RZ\CommonMark\Ext\Footnote\Event\GatherFootnotesListener;
use RZ\CommonMark\Ext\Footnote\Event\NumberFootnotesListener;
use RZ\CommonMark\Ext\Footnote\Parser\AnonymousFootnoteRefParser;
use RZ\CommonMark\Ext\Footnote\Parser\FootnoteBlockParser;
use RZ\CommonMark\Ext\Footnote\Parser\FootnoteRefParser;
use RZ\CommonMark\Ext\Footnote\Renderer\FootnoteBackrefRenderer;
use RZ\CommonMark\Ext\Footnote\Renderer\FootnoteContainerRenderer;
use RZ\CommonMark\Ext\Footnote\Renderer\FootnoteRefRenderer;
use RZ\CommonMark\Ext\Footnote\Renderer\FootnoteRenderer;

class FootnoteExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addInlineParser(new AnonymousFootnoteRefParser(), 35);
        $environment->addInlineParser(new FootnoteRefParser(), 51);
        $environment->addBlockParser(new FootnoteBlockParser(), 51);

        $environment->addInlineRenderer(FootnoteRef::class, new FootnoteRefRenderer());
        $environment->addInlineRenderer(FootnoteBackref::class, new FootnoteBackrefRenderer());
        $environment->addBlockRenderer(FootnoteContainer::class, new FootnoteContainerRenderer());
        $environment->addBlockRenderer(Footnote::class, new FootnoteRenderer());

        $environment->addEventListener(DocumentParsedEvent::class, [new AnonymousFootnotesListener($environment), 'onDocumentParsed']);
        $environment->addEventListener(DocumentParsedEvent::class, [new NumberFootnotesListener($environment), 'onDocumentParsed']);
        $environment->addEventListener(DocumentParsedEvent::class, [new GatherFootnotesListener($environment), 'onDocumentParsed']);
    }
}
