<?php

namespace PhpDocBlockChecker\Test\Unit\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\FileParser\FileParser;
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

        $this->filePath = FIXTURES . 'TestClass.php';
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

        $class = $classes['Fixtures\TestClass'];
        $this->assertEquals('Fixtures\TestClass', $class['name']);
        $this->assertEquals(null, $class['docblock']);
    }

    public function testWithNoReturn()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClass::emptyMethod'];
        $this->assertFalse($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }

    public function testWithNoParams()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClass::emptyMethod'];
        $this->assertEmpty($method->getParams());
    }

    public function testWithParams()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClass::withParams'];
        $types = [];
        foreach ($method->getParams() as $name => $type) {
            $types[$name] = (string) $type;
        }

        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $types);
    }

    public function testWithReturn()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClass::withReturn'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }

    public function testNullable()
    {
        $method = $this->fileInfo->getMethods()['Fixtures\TestClass::withReturn'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }
}
