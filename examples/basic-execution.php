<?php
use com\selfcoders\parallelprocesses\Events;
use com\selfcoders\parallelprocesses\ParallelExecutor;
use com\selfcoders\parallelprocesses\Process;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__ . "/../vendor/autoload.php";

$eventDispatcher = new EventDispatcher;

$eventDispatcher->addListener(Events::PROCESS_FINISHED, function () {
    fwrite(STDERR, "*** Process finished\n");
});

$process1 = new Process("ls");
$process2 = new Process("date");
$process3 = new Process("sleep 5; ls");
$process4 = new Process("sleep 5; date");

$process1->setEventDispatcher($eventDispatcher);
$process2->setEventDispatcher($eventDispatcher);
$process3->setEventDispatcher($eventDispatcher);
$process4->setEventDispatcher($eventDispatcher);

$executor = new ParallelExecutor;

$executor->add($process1);
$executor->add($process2);
$executor->add($process3);
$executor->add($process4);

$executor->run(function ($type, $buffer, Process $process) {
    fwrite(STDERR, "*** Got data from process " . $process->getCommandLine() . "\n");
    if ($type === Process::ERR) {
        fwrite(STDERR, $buffer);
    } else {
        fwrite(STDOUT, $buffer);
    }
});

fwrite(STDERR, "*** All processes finished ***\n");