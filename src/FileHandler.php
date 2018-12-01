<?php
namespace Rumd3x\Persistence;

class FileHandler
{
    /**
     * @var File
     */
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    private function hasValidHandle()
    {
        return $this->file->getHandle() !== false;
    }

    private function lock()
    {
        return flock($this->file->getHandle(), LOCK_EX);
    }

    private function unlock()
    {
        return flock($this->file->getHandle(), LOCK_UN);
    }

    /**
     * Retrieves first line from the file and erases it from it
     *
     * @return String
     */
    public function pop()
    {
        if (!$this->hasValidHandle()) {
            return false;
        }
        $this->lock();

        $handle = $this->file->getHandle();
        rewind($handle);

        $firstline = false;
        $offset = 0;
        $len = filesize($this->file->getPath());
        while (($line = fgets($handle, 4096)) !== false) {
            if (!$firstline) {
                $firstline = $line;
                $offset = strlen($firstline);
            }
            $pos = ftell($handle);
            fseek($handle, $pos - strlen($line) - $offset);
            fputs($handle, $line);
            fseek($handle, $pos);
        }
        fflush($handle);
        ftruncate($handle, ($len - $offset));

        $this->unlock();
        return $firstline;
    }

    public function append(String $data)
    {
        file_put_contents($this->file->getPath(), "{$data}\r\n", FILE_APPEND | LOCK_EX);
        return $this;
    }
}
