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
        $nextCounter = 1;
        $usedLabels = [];
        $usedCounters = [];

        while ($event = $walker->next()) {
            $node = $event->getNode();

            if ($node instanceof FootnoteRef && $event->isEntering()) {
                $existingReference = $node->getReference();
                $label = $existingReference->getLabel();
                $counter = $nextCounter;
                $canIncrementCounter = true;

                if (array_key_exists($label, $usedLabels)) {
                    /*
                     * Reference is used again, we need to point
                     * to the same footnote. But with a different ID
                     */
                    $counter = $usedCounters[$label];
                    $label = $label . '__' . ++$usedLabels[$label];
                    $canIncrementCounter = false;
                }
                // rewrite reference title to use a numeric link
                $newReference = new Reference(
                    $label,
                    $existingReference->getDestination(),
                    (string) $counter
                );
                // Override reference with numeric link
                $node->setReference($newReference);
                $document->getReferenceMap()->addReference($newReference);

                /*
                 * Store created references in document for
                 * creating FootnoteBackrefs
                 */
                if (false === $document->getData($existingReference->getDestination(), false)) {
                    $document->data[$existingReference->getDestination()] = [];
                }
                $document->data[$existingReference->getDestination()][] = $newReference;

                $usedLabels[$label] = 1;
                $usedCounters[$label] = $nextCounter;

                if ($canIncrementCounter) {
                    $nextCounter++;
                }
            }
        }
    }
}
