<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Parser;

use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Reference\Reference;
use RZ\CommonMark\Ext\Footnote\FootnoteRef;

final class AnonymousFootnoteRefParser implements InlineParserInterface
{
    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return ['^'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $container = $inlineContext->getContainer();
        $cursor = $inlineContext->getCursor();

        $m = $cursor->match('/\^\[[^\n^\]]+\]/');
        if ($m === null) {
            return false;
        }

        if (preg_match('#\^\[([^\]]+)\]#', $m, $matches) > 0) {
            $reference = $this->getReference($matches[1]);
            $container->appendChild(new FootnoteRef($reference, $matches[1]));
            return true;
        }

        return false;
    }

    protected function getReference(string $label)
    {
        return new Reference(uniqid('fn'), '#fn-', $label);
    }
}
