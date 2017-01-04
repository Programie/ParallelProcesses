<?php
namespace com\selfcoders\parallelprocesses;

use Symfony\Component\EventDispatcher\Event;

class ProcessEvent extends Event
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }
}