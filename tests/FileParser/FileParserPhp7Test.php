<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpParser\ParserFactory;

/**
 * @requires PHP 7.0
 */
class FileParserPhp7Test extends \PHPUnit\Framework\TestCase
{
    protected $filePath = __DIR__ . '/TestClassPhp7.php';
    protected $fileInfo;

    protected function setUp(): void
    {
        $fileParser = new FileParser(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

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
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string', $method->getReturnType()->toString());
        $this->assertEquals('string', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string|null', $method->getReturnType()->toString());
        $this->assertEquals('string|null', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithMixedOrderNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withMixedOrderNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string|null', $method->getReturnType()->toString());
        $this->assertEquals('string|null', $method->getDocBlock()->getReturnType()->toString());
    }
}
