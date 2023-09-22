<?php

namespace PhpDocBlockChecker\Test\Unit\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\Test\Fixture\TestClass;
use PhpParser\ParserFactory;
use ReflectionClass;

class FileParserTest extends \PHPUnit\Framework\TestCase
{
    protected $filePath;
    protected $fileInfo;

    protected function setUp(): void
    {
        $fileParser = new FileParser(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

        $this->filePath = (new ReflectionClass(TestClass::class))->getFileName();

        $this->fileInfo = $fileParser->parseFile($this->filePath);
    }

    public function testFileLoaded()
    {
        $this->assertEquals($this->filePath, $this->fileInfo->getFileName());
    }

    public function testClassLoaded()
    {
        $classes = $this->fileInfo->getClasses();
        $this->assertCount(1, $classes);

        $class = $classes[TestClass::class];
        $this->assertEquals(TestClass::class, $class['name']);
        $this->assertEquals(null, $class['docblock']);
    }

    public function testWithNoReturn()
    {
        $method = $this->fileInfo->getMethods()[TestClass::class . '::emptyMethod'];
        $this->assertFalse($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }

    public function testWithNoParams()
    {
        $method = $this->fileInfo->getMethods()[TestClass::class . '::emptyMethod'];
        $this->assertEmpty($method->getParams());
    }

    public function testWithParams()
    {
        $method = $this->fileInfo->getMethods()[TestClass::class . '::withParams'];
        $types = [];
        foreach ($method->getParams() as $name => $type) {
            $types[$name] = (string) $type;
        }

        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $types);
    }

    public function testWithReturn()
    {
        $method = $this->fileInfo->getMethods()[TestClass::class . '::withReturn'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }
}
