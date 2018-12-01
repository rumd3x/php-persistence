<?php
namespace Rumd3x\Persistence;

class File implements FileInterface
{
    const READONLY = 'r';
    const WRITEONLY = 'c';
    const READWRITE = 'c+';

    private $path;
    private $handle;
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
        fclose($this->handle);
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
