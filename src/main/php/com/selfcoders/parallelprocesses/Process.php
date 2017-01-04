<?php
namespace com\selfcoders\parallelprocesses;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Process\Process as BaseProcess;

class Process extends BaseProcess
{
    /**
     * @var bool
     */
    private $running;
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function process()
    {
        $this->updateStatus(false);
        $this->checkTimeout();

        $running = $this->isRunning();

        // Process has ended if running state has changed from true to false
        if ($this->eventDispatcher !== null and $this->running and !$running) {
            $this->eventDispatcher->dispatch(Events::PROCESS_FINISHED, new ProcessEvent($this));
        }

        $this->running = $running;
    }
}