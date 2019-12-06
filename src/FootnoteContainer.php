<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Cursor;

final class FootnoteContainer extends AbstractBlock
{
    public function canContain(AbstractBlock $block): bool
    {
        return $block instanceof Footnote;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return false;
    }
}
