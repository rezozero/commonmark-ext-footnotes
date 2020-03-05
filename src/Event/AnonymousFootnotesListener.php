<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Event;

use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Reference\Reference;
use RZ\CommonMark\Ext\Footnote\Footnote;
use RZ\CommonMark\Ext\Footnote\FootnoteBackref;
use RZ\CommonMark\Ext\Footnote\FootnoteRef;

final class AnonymousFootnotesListener
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
        while ($event = $walker->next()) {
            $node = $event->getNode();
            if ($node instanceof FootnoteRef && $event->isEntering() && null !== $text = $node->getContent()) {
                // Anonymous footnote needs to create a footnote from its content
                $existingReference = $node->getReference();
                $reference = new Reference(
                    $existingReference->getLabel(),
                    '#fn-ref-' . $existingReference->getLabel(),
                    $existingReference->getTitle()
                );
                $footnote = new Footnote($reference);
                $footnote->addBackref(new FootnoteBackref($reference));
                $paragraph = new Paragraph();
                $paragraph->appendChild(new HtmlInline($text));
                $footnote->appendChild($paragraph);
                $document->appendChild($footnote);
            }
        }
    }
}
