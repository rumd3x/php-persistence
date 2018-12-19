<?php
namespace Rumd3x\Persistence;

use Exception;

class LockException extends Exception
{
    /**
     * @var FileHandler
     */
    private $fileHandler;

    public function __construct(FileHandler $fileHandler, $message, $code = 0, Exception $previous = null)
    {
        $this->fileHandler = $fileHandler;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return FileHandler
     */
    public function getFileHandler()
    {
        return $this->fileHandler;
    }
}
