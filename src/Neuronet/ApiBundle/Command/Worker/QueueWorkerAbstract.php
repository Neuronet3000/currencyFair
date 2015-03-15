<?php
namespace Neuronet\ApiBundle\Command\Worker;

use Symfony\Component\Console\Input\InputOption;
use Neuronet\ApiBundle\DataQueue\DataQueueAbstract;
use Neuronet\ApiBundle\DataQueue\QueueMessage;

abstract class QueueWorkerAbstract extends WorkerAbstract
{
    protected $inputDataQueue;
    protected $chunkSize = 1;
    protected $sleepPeriod = 1;
    
    public function __construct(
            DataQueueAbstract $inputDataQueue
    )
    {
        $this->inputDataQueue = $inputDataQueue;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->addOption('chunkSize', 'chsz', InputOption::VALUE_OPTIONAL, 'size of messages chunk, recevied from queue on one iteration', 1)
            ->addOption('sleepPeriod', 'sp', InputOption::VALUE_OPTIONAL, 'worker sleep period between iterations', 1)
        ;
    }
    
    protected function onWorkerStart()
    {
        $chunkSize = intval($this->input->getOption('chunkSize'));
        if ($chunkSize <= 0) {
            throw new \InvalidArgumentException("chunkSize has invalid value", E_USER_ERROR);
        }
        $sleepPeriod = intval($this->input->getOption('sleepPeriod'));
        if ($sleepPeriod <= 0) {
            throw new \InvalidArgumentException("sleepPeriod has invalid value", E_USER_ERROR);
        }
        $this->chunkSize = $chunkSize;
        $this->sleepPeriod = $sleepPeriod;
    }
    
    protected function processMessage(QueueMessage $message)
    {
        $this->keepWorking = false;
        return false;
    }
    
    protected function onChunkStart() {}
    protected function onChunkEnd() {}

    protected function workerProcess()
    {
        $this->onChunkStart();
        for ($i = 0; $i < $this->chunkSize; $i++) {
            $messg = $this->inputDataQueue->getMessageFromQueue();
            if (is_null($messg)) {
                break;
            }
            
            $processResult = $this->processMessage($messg);
            if ($processResult) {
                $this->inputDataQueue->markMessageDone($messg);
            } else  {
                $this->inputDataQueue->markMessageFail($messg);
            }
        }
        $this->onChunkEnd();
        sleep($this->sleepPeriod);
    }
}

