<?php
namespace Rumd3x\Persistence;

/**
 * This class holds the implementation of a file
 */
class File implements FileInterface
{
    const READONLY = 'r';
    const WRITEONLY = 'c';
    const READWRITE = 'c+';

    /**
     * Holds the path to the file
     *
     * @var String
     */
    private $path;

    /**
     * Holds the resource handle for the file in the SO
     * Not to be confused with the FileHandler class
     *
     * @var Resource
     */
    private $handle;

    /**
     * Holds the mode in which the file is open
     *
     * @var String
     */
    private $mode;

    public function __construct(String $path, String $mode = File::READWRITE)
    {
        $this->path = $path;
        $this->mode = $mode;
        $this->open($path, $mode);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function open()
    {
        $this->handle = fopen($this->path, $this->mode);
        return $this;
    }

    public function close()
    {
        @fclose($this->handle);
        return $this;
    }

    public function truncate(int $bytes = 0)
    {
        ftruncate($this->handle, $bytes);
        return $this;
    }

    public function delete()
    {
        return unlink($this->handle);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHandle()
    {
        return $this->handle;
    }
}
