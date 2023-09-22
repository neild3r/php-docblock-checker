<?php

namespace PhpDocBlockChecker\FileProvider;

use PhpDocBlockChecker\Config\Config;

class FileProviderFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testGetFileProvider()
    {
        $provider = FileProviderFactory::getFileProvider(Config::fromArray([]));
        $this->assertInstanceOf(FileProviderInterface::class, $provider);
    }
}
