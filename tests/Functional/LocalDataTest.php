<?php

declare(strict_types=1);

namespace League\CommonMark\Ext\Footnote\Tests\Functional;

use League\CommonMark\DocParser;
use League\CommonMark\DocParserInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\Extras\CommonMarkExtrasExtension;
use League\CommonMark\HtmlRenderer;
use PHPUnit\Framework\TestCase;
use RZ\CommonMark\Ext\Footnote\FootnoteExtension;

/**
 * @internal
 */
class LocalDataTest extends TestCase
{
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var DocParser
     */
    private $parser;
    /**
     * @var Environment
     */
    private $extraEnvironment;
    /**
     * @var DocParser
     */
    private $extraParser;

    protected function setUp(): void
    {
        /*
         * Test with minimal extensions
         */
        $this->environment = Environment::createCommonMarkEnvironment();
        $this->environment->addExtension(new FootnoteExtension());
        $this->parser = new DocParser($this->environment);

        /*
         * Test with other extensions
         */
        $this->extraEnvironment = Environment::createCommonMarkEnvironment();
        $this->extraEnvironment->addExtension(new AutolinkExtension());
        $this->extraEnvironment->addExtension(new SmartPunctExtension());
        $this->extraEnvironment->addExtension(new StrikethroughExtension());
        $this->extraEnvironment->addExtension(new TableExtension());
        $this->extraEnvironment->addExtension(new TaskListExtension());
        $this->extraEnvironment->addExtension(new FootnoteExtension());
        $this->extraParser = new DocParser($this->extraEnvironment);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRenderer(string $markdown, string $html, string $testName): void
    {
        $renderer = new HtmlRenderer($this->environment);
        $this->assertCommonMark($this->parser, $renderer, $markdown, $html, $testName);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testExtraRenderer(string $markdown, string $html, string $testName): void
    {
        $renderer = new HtmlRenderer($this->extraEnvironment);
        $this->assertCommonMark($this->extraParser, $renderer, $markdown, $html, $testName);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        $ret = [];
        foreach (glob(__DIR__.'/data/*.md') as $markdownFile) {
            $testName = basename($markdownFile, '.md');
            $markdown = file_get_contents($markdownFile);
            $html = file_get_contents(__DIR__.'/data/'.$testName.'.html');

            $ret[] = [$markdown, $html, $testName];
        }

        return $ret;
    }

    protected function assertCommonMark(
        DocParserInterface $parser,
        ElementRendererInterface $renderer,
        $markdown,
        $html,
        $testName
    ): void {
        $documentAST = $parser->parse($markdown);
        $actualResult = $renderer->renderBlock($documentAST);

        $failureMessage = sprintf('Unexpected result for "%s" test', $testName);
        $failureMessage .= "\n=== markdown ===============\n".$markdown;
        $failureMessage .= "\n=== expected ===============\n".$html;
        $failureMessage .= "\n=== got ====================\n".$actualResult;

        $this->assertEquals($html, $actualResult, $failureMessage);
    }
}
