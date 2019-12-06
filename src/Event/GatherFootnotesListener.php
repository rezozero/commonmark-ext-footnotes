<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Event;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use RZ\CommonMark\Ext\Footnote\Footnote;
use RZ\CommonMark\Ext\Footnote\FootnoteContainer;

final class GatherFootnotesListener
{
    private $environment;

    public function __construct(EnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    public function onDocumentParsed(DocumentParsedEvent $event)
    {
        $document = $event->getDocument();
        $walker = $document->walker();
        $footnotes = [];
        while ($event = $walker->next()) {
            $node = $event->getNode();
            if ($node instanceof Footnote && $event->isEntering()) {
                // Look for existing reference with footnote label
                $ref = $document->getReferenceMap()->getReference($node->getReference()->getLabel());
                // Use numeric title to get footnotes order
                $footnotes[intval($ref->getTitle())] = $node;
            }
        }

        $container = $this->getFootnotesContainer($document);
        ksort($footnotes);
        foreach ($footnotes as $footnote) {
            $container->appendChild($footnote);
        }
    }

    protected function getFootnotesContainer(Document $document): FootnoteContainer
    {
        $footnoteContainer = new FootnoteContainer();
        $document->appendChild($footnoteContainer);

        return $footnoteContainer;
    }
}
