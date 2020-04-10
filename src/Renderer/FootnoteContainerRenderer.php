<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use RZ\CommonMark\Ext\Footnote\FootnoteContainer;

final class FootnoteContainerRenderer implements BlockRendererInterface
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
        if (!($block instanceof FootnoteContainer)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }
        $attrs = $block->getData('attributes', [
            'class' => 'footnotes',
            'role' => 'doc-endnotes'
        ]);

        return new HtmlElement(
            'div',
            $attrs,
            [
                new HtmlElement('hr', [], null, true),
                new HtmlElement(
                    'ol',
                    [],
                    $htmlRenderer->renderBlocks($block->children()),
                    true
                )
            ],
            true
        );
    }
}
