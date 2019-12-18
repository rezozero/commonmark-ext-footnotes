<?php
declare(strict_types=1);

namespace RZ\CommonMark\Ext\Footnote\Parser;

use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Reference\Reference;
use RZ\CommonMark\Ext\Footnote\FootnoteRef;

final class FootnoteRefParser implements InlineParserInterface
{
    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return ['['];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $container = $inlineContext->getContainer();
        $cursor = $inlineContext->getCursor();
        if ($cursor->getCharacter() !== '[') {
            return false;
        }
        $state = $cursor->saveState();

        $m = $cursor->match('#\[\^([^\]]+)\]#');
        if ($m !== null) {
            if (preg_match('#\[\^([^\]]+)\]#', $m, $matches) > 0) {
                $container->appendChild(new FootnoteRef($this->getReference($matches[1])));
                return true;
            }
        }

        $cursor->restoreState($state);
        return false;
    }

    protected function getReference(string $label)
    {
        return new Reference($label, '#fn-', $label);
    }
}
