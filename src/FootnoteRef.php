<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote;

use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Reference\ReferenceInterface;

final class FootnoteRef extends AbstractInline
{
    /**
     * @var ReferenceInterface
     */
    protected $reference;
    /**
     * @var string|null
     */
    protected $content;

    public function __construct(ReferenceInterface $reference, ?string $content = null)
    {
        $this->reference = $reference;
        $this->content = $content;
    }

    /**
     * @return ReferenceInterface
     */
    public function getReference(): ReferenceInterface
    {
        return $this->reference;
    }

    /**
     * @param ReferenceInterface $reference
     *
     * @return FootnoteRef
     */
    public function setReference(ReferenceInterface $reference): FootnoteRef
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
}
