<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Cursor;
use League\CommonMark\Reference\ReferenceInterface;

final class Footnote extends AbstractBlock
{
    /**
     * @var ReferenceInterface
     */
    protected $reference;

    /**
     * Footnote constructor.
     *
     * @param ReferenceInterface $reference
     */
    public function __construct(ReferenceInterface $reference)
    {
        $this->reference = $reference;
    }

    public function canContain(AbstractBlock $block): bool
    {
        return true;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return false;
    }

    /**
     * @return ReferenceInterface
     */
    public function getReference(): ReferenceInterface
    {
        return $this->reference;
    }
}
