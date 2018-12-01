<?php
namespace Rumd3x\Persistence;

class Engine
{
    private $driver;

    public function __construct(String $driver)
    {
        $this->driver = __DIR__ . '/' . $driver;
        $this->handler = new FileHandler(new File("{$this->driver}.pe"));
    }

    public function store($data)
    {
        $this->handler->append(serialize($data));
        return $this;
    }

    public function retrieve()
    {
        $data = $this->handler->pop();

        if (!$data) {
            return null;
        }

        return unserialize($data);
    }
}
