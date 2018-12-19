<?php
namespace Rumd3x\Persistence;

use Exception;

/**
 * This class handles a file
 */
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

    public function __destruct()
    {
        $this->unlock();
        if ($this->hasValidHandle()) {
            $this->file->close();
        }
    }

    /**
     * If the opened file has a valid resource handle
     *
     * @return bool
     */
    private function hasValidHandle()
    {
        $handle = $this->file->getHandle();
        $isHandleInvalid = $handle === false;
        $isHandleInvalid = $isHandleInvalid || !is_resource($handle);
        $isHandleInvalid = $isHandleInvalid || get_resource_type($handle) === 'Unknown';
        return !$isHandleInvalid;
    }

    /**
     * @return bool
     * @throws LockException
     */
    private function lock()
    {
        if (!flock($this->file->getHandle(), LOCK_EX)) {
            throw new LockException($this, "Error Locking File", 1);
        }
        return true;
    }

    /**
     * @return bool
     * @throws LockException
     */
    private function unlock()
    {
        if (!flock($this->file->getHandle(), LOCK_UN)) {
            throw new LockException($this, "Error Unlocking File", 2);
        }
        return true;
    }

    /**
     * Truncates the file
     *
     * @return bool
     */
    public function cleanFile()
    {
        $this->lock();
        $result = $this->file->truncate();
        $this->unlock();
        return $result;
    }

    /**
     * Retrieves first line from the file and erases it
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

    /**
     * Appends an string to the end of the file
     *
     * @param String $data
     * @return self
     */
    public function append(String $data)
    {
        file_put_contents($this->file->getPath(), "{$data}\r\n", FILE_APPEND | LOCK_EX);
        return $this;
    }
}
