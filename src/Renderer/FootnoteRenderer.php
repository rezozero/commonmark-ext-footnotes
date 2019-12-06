<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use RZ\CommonMark\Ext\Footnote\Footnote;
use RZ\CommonMark\Ext\Footnote\FootnoteBackref;

final class FootnoteRenderer implements BlockRendererInterface
{
    /**
     * @param AbstractBlock            $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement|string|null
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof Footnote)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }
        $attrs = $block->getData('attributes', [
            'class' => 'footnote',
            'id' => 'fn-' . $block->getReference()->getLabel()
        ]);

        $block->lastChild()->appendChild(new FootnoteBackref($block->getReference()));

        return new HtmlElement(
            'li',
            $attrs,
            $htmlRenderer->renderBlocks($block->children()),
            true
        );
    }
}