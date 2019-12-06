<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Event;

use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Reference\Reference;
use RZ\CommonMark\Ext\Footnote\FootnoteRef;

final class NumberFootnotesListener
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
        $counter = 1;
        while ($event = $walker->next()) {
            $node = $event->getNode();

            if ($node instanceof FootnoteRef && $event->isEntering()) {
                // Anonymous footnote need to create a footnote from its content
                $existingReference = $node->getReference();
                // rewrite reference
                $newReference = new Reference(
                    $existingReference->getLabel(),
                    $existingReference->getDestination(),
                    (string) $counter
                );
                $node->setReference($newReference);
                $document->getReferenceMap()->addReference($newReference);
                $counter++;
            }
        }
    }
}
