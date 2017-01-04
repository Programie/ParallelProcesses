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
            $this->processCycle();

            usleep(1000);
        } while ($this->countRunning());
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

    public function processCycle()
    {
        /**
         * @var $process Process
         */
        foreach ($this->processes as $process) {
            if (!$process->isRunning()) {
                continue;
            }

            $process->process();
        }
    }
}