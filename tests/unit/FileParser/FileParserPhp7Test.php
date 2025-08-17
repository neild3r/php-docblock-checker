<?php

namespace PhpDocBlockChecker\Test\Unit\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpParser\ParserFactory;

/**
 * @requires PHP 7.0
 */
class FileParserPhp7Test extends \PHPUnit\Framework\TestCase
{
    protected $filePath;
    protected $fileInfo;

    protected function setUp(): void
    {
        $fileParser = new FileParser(
            (new ParserFactory())->createForHostVersion(),
            new DocblockParser()
        );

        $this->filePath = FIXTURES . 'TestClassPhp7.php';
        $this->fileInfo = $fileParser->parseFile($this->filePath);
    }

    public function testFileLoaded()
    {
        $this->assertEquals($this->filePath, $this->fileInfo->getFileName());
    }

    /**
     * @requires PHP 7.0
     */
    public function testWithReturnHint()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClassPhp7::withReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string', $method->getReturnType()->toString());
        $this->assertEquals('string', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClassPhp7::withNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string|null', $method->getReturnType()->toString());
        $this->assertEquals('string|null', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithMixedOrderNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClassPhp7::withMixedOrderNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('null|string', $method->getReturnType()->toString());
        $this->assertEquals('null|string', $method->getDocBlock()->getReturnType()->toString());
    }
}
