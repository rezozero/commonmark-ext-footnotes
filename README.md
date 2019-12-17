CommonMark footnotes Extension
==============================

The Footnotes extension adds the ability to create footnotes in CommonMark documents.

[![Packagist Version](https://img.shields.io/packagist/v/rezozero/commonmark-ext-footnotes)](https://packagist.org/packages/rezozero/commonmark-ext-footnotes)
[![Build Status](https://travis-ci.org/rezozero/commonmark-ext-footnotes.svg?branch=master)](https://travis-ci.org/rezozero/commonmark-ext-footnotes)

Installation
------------

This project can be installed via Composer:

    composer require rezozero/commonmark-ext-footnotes

Usage
-----

Configure your `Environment` as usual and simply add the `FootnoteExtension` provided by this package:

```php
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use RZ\CommonMark\Ext\Footnote\FootnoteExtension;

// Obtain a pre-configured Environment with all the standard CommonMark parsers/renderers ready-to-go
$environment = Environment::createCommonMarkEnvironment();

// Add this extension
$environment->addExtension(new FootnoteExtension());

// Instantiate the converter engine and start converting some Markdown!
$converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

echo $converter->convertToHtml('# Hello World!');
```

Syntax
------

Code:
```markdown
Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. 
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi[^note1] leo risus, porta ac consectetur ac.

[^note1]: Elit Malesuada Ridiculus
```

Result:
```html
<p>
Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. 
Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Morbi<a class="footnote-ref" id="fn-ref-note1" href="#fn-note1"><sup>1</sup></a> leo risus, porta ac consectetur ac.
</p>
<div class="footnotes">
    <hr>
    <ol>
        <li class="footnote" id="fn-note1">
            <p>Elit Malesuada Ridiculus<a class="footnote-backref" rev="footnote" href="#fn-ref-note1">↩</a></p>
        </li>
    </ol>
</div>
```
