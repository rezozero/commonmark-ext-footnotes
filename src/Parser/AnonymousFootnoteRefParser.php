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
        $nextChar = $cursor->peek();
        if ($nextChar !== '[') {
            return false;
        }
        $state = $cursor->saveState();

        $m = $cursor->match('/\^\[[^\n^\]]+\]/');
        if ($m !== null) {
            if (\preg_match('#\^\[([^\]]+)\]#', $m, $matches) > 0) {
                $reference = $this->getReference($matches[1]);
                $container->appendChild(new FootnoteRef($reference, $matches[1]));
                return true;
            }
        }

        $cursor->restoreState($state);
        return false;
    }

    protected function getReference(string $label)
    {
        $refLabel = Reference::normalizeReference($label);
        if (\function_exists('mb_strtolower')) {
            $refLabel = \mb_strtolower(\str_replace(' ', '-', $refLabel));
        } else {
            $refLabel = \strtolower(\str_replace(' ', '-', $refLabel));
        }
        $refLabel = \substr($refLabel, 0, 20);
        return new Reference($refLabel, '#fn-' . $refLabel, $label);
    }
}
