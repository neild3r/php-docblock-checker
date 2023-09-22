<?php

namespace PhpDocBlockChecker\Test\Unit\FileProvider;

use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\FileProvider\FileProviderFactory;
use PhpDocBlockChecker\FileProvider\FileProviderInterface;

class FileProviderFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testGetFileProvider()
    {
        $provider = FileProviderFactory::getFileProvider(Config::fromArray([]));
        $this->assertInstanceOf(FileProviderInterface::class, $provider);
    }
}
