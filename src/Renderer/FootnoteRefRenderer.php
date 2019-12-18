<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Renderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use RZ\CommonMark\Ext\Footnote\FootnoteRef;

final class FootnoteRefRenderer implements InlineRendererInterface
{
    /**
     * @param AbstractInline           $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement|string|null
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof FootnoteRef)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        return new HtmlElement(
            'a',
            [
                'class' => 'footnote-ref',
                'id' => 'fn-ref-' . $inline->getReference()->getLabel(),
                'href' => $inline->getReference()->getDestination() . $inline->getReference()->getLabel()
            ],
            new HtmlElement('sup', [], $inline->getReference()->getTitle()),
            true
        );
    }
}
