<?php
namespace Neuronet\ApiBundle\DataQueue;

class QueueMessageFactory
{
    public function getQueueMessage($data, $transportSpecificData)
    {
        $messg = new QueueMessage();
        $messg->unserialize($data);
        $messg->setTransportSpecificData($transportSpecificData);
        
        return $messg;
    }
}

