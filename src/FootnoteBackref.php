<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote;

use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Reference\ReferenceInterface;

final class FootnoteBackref extends AbstractInline
{
    /**
     * @var ReferenceInterface
     */
    protected $reference;

    /**
     * FootnoteBackref constructor.
     *
     * @param ReferenceInterface $reference
     */
    public function __construct(ReferenceInterface $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return ReferenceInterface
     */
    public function getReference(): ReferenceInterface
    {
        return $this->reference;
    }
}
