<?php
namespace com\selfcoders\parallelprocesses;

class ParallelExecutor
{
    /**
     * @var Process[]
     */
    protected $processes;

    /**
     * @param Process $process
     */
    public function add(Process $process)
    {
        $this->processes[] = $process;
    }

    /**
     * @param callable|null $callback
     */
    public function start(callable $callback = null)
    {
        foreach ($this->processes as $process) {
            $process->start($callback);
        }
    }

    /**
     * @param callable|null $callback
     */
    public function run(callable $callback = null)
    {
        $this->start($callback);

        do {
            usleep(1000);
        } while ($this->processCycle());
    }

    /**
     * @return int
     */
    public function countRunning()
    {
        $running = 0;

        foreach ($this->processes as $process) {
            if ($process->isRunning()) {
                $running++;
            }
        }

        return $running;
    }

    /**
     * @return bool
     */
    public function processCycle()
    {
        $foundRunning = false;

        /**
         * @var $process Process
         */
        foreach ($this->processes as $process) {
            $process->process();

            if (!$process->isRunning()) {
                continue;
            }

            $foundRunning = true;
        }

        return $foundRunning;
    }
}