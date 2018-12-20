<?php
namespace Rumd3x\Persistence;

/**
 * The Persistence engine. Used to store persistent data and retrieve it.
 */
class Engine
{
    /**
     * @var FileHandler
     */
    private $driver;
    
    /**
     * Driver should be the full path + file name without extension.
     *
     * @param string $driver
     */
    public function __construct(String $driver)
    {
        $this->driver = $driver;
        $this->handler = new FileHandler(new File("{$this->driver}.pe"));
    }

    /**
     * Stores data at the bottom of the stack.
     *
     * @param mixed $data
     * @return self
     */
    public function store($data)
    {
        $this->handler->append(serialize($data));
        return $this;
    }

    /**
     * Removes data from the bottom of the stack and returns it
     *
     * @return mixed
     */
    public function retrieve()
    {
        $data = $this->handler->pop();

        if (!$data) {
            return null;
        }

        $unserialized = @unserialize($data);
        if ($unserialized === false) {
            $this->clear();
            return null;
        }

        return $unserialized;
    }

    /**
     * Removes all stored data
     *
     * @return bool
     */
    public function clear()
    {
        return $this->handler->cleanFile();
    }
}
