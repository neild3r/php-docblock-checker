<?php

namespace PhpDocBlockChecker\FileProvider;

class StdinFileProvider implements FileProviderInterface
{
    use ExcludeFileTrait;

    /**
     * @var resource
     */
    private $handle;

    /**
     * StdinFileProvider constructor.
     * @param resource $handle
     * @param array $excludes
     */
    public function __construct($handle, array $excludes)
    {
        $this->handle = $handle;
        $this->excludes = $excludes;
    }

    /**
     * Close any open handles
     */
    public function __destruct()
    {
        // Close the file handle if its still open
        if ($this->fileHandleOpen()) {
            fclose($this->handle);
        }
    }

    /**
     * @return bool
     */
    private function fileHandleOpen()
    {
        $types = ['file' => 'file', 'stream' => 'stream'];

        return isset($types[get_resource_type($this->handle)]);
    }

    /**
     * @return \ArrayIterator
     */
    public function getFileIterator()
    {
        if (!$this->fileHandleOpen()) {
            return new \ArrayIterator();
        }

        $files = [];
        while (($line = fgets($this->handle)) !== false) {
            $line = rtrim($line, "\r\n");
            $file = new \SplFileInfo($line);
            if ($this->isFileExcluded('', $file)) {
                continue;
            }
            $files[] = $file;
        }

        return new \ArrayIterator($files);
    }
}
