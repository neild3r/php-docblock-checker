<?php

namespace PhpDocBlockChecker\FileParser;

use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpParser\ParserFactory;

class FileParserTest extends \PHPUnit\Framework\TestCase
{
    protected $filePath = __DIR__ . '/TestClass.php';
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

    public function testClassLoaded()
    {
        $classes = $this->fileInfo->getClasses();
        $this->assertCount(1, $classes);

        $class = $classes['PhpDocBlockChecker\FileParser\TestClass'];
        $this->assertEquals('PhpDocBlockChecker\FileParser\TestClass', $class['name']);
        $this->assertEquals(null, $class['docblock']);
    }

    public function testWithNoReturn()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::emptyMethod'];
        $this->assertFalse($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }

    public function testWithNoParams()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::emptyMethod'];
        $this->assertEmpty($method->getParams());
    }

    public function testWithParams()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withParams'];
        $types = [];
        foreach ($method->getParams() as $name => $type) {
            $types[$name] = (string) $type;
        }

        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $types);
    }

    public function testWithReturn()
    {
        $method = $this->fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::withReturn'];
        $this->assertTrue($method->hasReturn());
        $this->assertEquals(null, $method->getReturnType());
    }
}
