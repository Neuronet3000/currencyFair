<?php
namespace Neuronet\ApiBundle\DataQueue;

abstract class DataQueueAbstract
{
    protected $dataMessageFactory;
    public function putMessageToQueue(QueueMessage $message) {}
    public function getMessageFromQueue() {}
    public function markMessageDone(QueueMessage $message) {}
    public function markMessageFail(QueueMessage $message) {}
    
    public function setMessageFactory(QueueMessageFactory $factory)
    {
        $this->dataMessageFactory = $factory;
    }
}
