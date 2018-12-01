<?php
namespace Rumd3x\Persistence;

interface FileInterface
{
    public function open();
    public function close();
    public function delete();
    public function truncate(int $bytes);
}
