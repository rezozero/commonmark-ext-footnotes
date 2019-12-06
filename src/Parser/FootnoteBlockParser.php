<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Parser;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Reference\Reference;
use League\CommonMark\Util\RegexHelper;
use RZ\CommonMark\Ext\Footnote\Footnote;

final class FootnoteBlockParser implements BlockParserInterface
{
    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     *
     * @return bool
     */
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            return false;
        }

        $match = RegexHelper::matchAll('/^\[\^([^\n^\]]+)\]\:\s/', $cursor->getLine(), $cursor->getNextNonSpacePosition());
        if (!$match) {
            return false;
        }

        $cursor->advanceToNextNonSpaceOrTab();
        $cursor->advanceBy(\strlen($match[0]));
        $str = $cursor->getRemainder();
        $str = \preg_replace('/^\[\^([^\n^\]]+)\]\:\s/', '', $str);

        if (preg_match('/^\[\^([^\n^\]]+)\]\:\s/', $match[0], $matches) > 0) {
            $context->addBlock(new Footnote($this->getReference($matches[1])));
            $context->setBlocksParsed(true);
            return true;
        }
        return false;
    }

    protected function getReference(string $label)
    {
        return new Reference($label, '#fn-ref-', $label);
    }
}
