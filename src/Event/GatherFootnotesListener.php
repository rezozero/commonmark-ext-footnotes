<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Event;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Reference\Reference;
use RZ\CommonMark\Ext\Footnote\Footnote;
use RZ\CommonMark\Ext\Footnote\FootnoteBackref;
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
                if (null !== $ref) {
                    // Use numeric title to get footnotes order
                    $footnotes[intval($ref->getTitle())] = $node;
                } else {
                    // Footnote call is missing, append footnote at the end
                    $footnotes[INF] = $node;
                }

                /*
                 * Look for all footnote refs pointing to this footnote
                 * and create each footnote backrefs.
                 */
                $backrefs = $document->getData('#fn:' . $node->getReference()->getDestination(), []);
                /** @var Reference $backref */
                foreach ($backrefs as $backref) {
                    $node->addBackref(new FootnoteBackref(new Reference(
                        $backref->getLabel(),
                        '#fnref:' . $backref->getLabel(),
                        $backref->getTitle()
                    )));
                }
            }
        }

        if (count($footnotes) > 0) {
            // Only add a footnote container if there are any
            $container = $this->getFootnotesContainer($document);
            \ksort($footnotes);
            foreach ($footnotes as $footnote) {
                $container->appendChild($footnote);
            }
        }
    }

    protected function getFootnotesContainer(Document $document): FootnoteContainer
    {
        $footnoteContainer = new FootnoteContainer();
        $document->appendChild($footnoteContainer);

        return $footnoteContainer;
    }
}
