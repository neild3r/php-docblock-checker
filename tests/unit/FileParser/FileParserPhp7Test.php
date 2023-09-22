<?php

namespace PhpDocBlockChecker\Test\Unit\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\Test\Fixture\TestClassPhp7;
use PhpParser\ParserFactory;
use ReflectionClass;

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
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

        $this->filePath = (new ReflectionClass(TestClassPhp7::class))->getFileName();

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
        $method = $this->fileInfo->getMethods()[TestClassPhp7::class . '::withReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string', $method->getReturnType()->toString());
        $this->assertEquals('string', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()[TestClassPhp7::class . '::withNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string|null', $method->getReturnType()->toString());
        $this->assertEquals('string|null', $method->getDocBlock()->getReturnType()->toString());
    }

    /**
     * @requires PHP 7.1
     */
    public function testWithMixedOrderNullableReturnHint()
    {
        $method = $this->fileInfo->getMethods()[TestClassPhp7::class . '::withMixedOrderNullableReturnHint'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals('string|null', $method->getReturnType()->toString());
        $this->assertEquals('string|null', $method->getDocBlock()->getReturnType()->toString());
    }
}
