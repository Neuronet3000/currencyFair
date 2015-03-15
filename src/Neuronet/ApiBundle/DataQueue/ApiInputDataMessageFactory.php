<?php
namespace Neuronet\ApiBundle\DataQueue;

class ApiInputDataMessageFactory extends QueueMessageFactory
{
    public function getQueueMessage($data, $transportSpecificData)
    {
        $messg = new ApiInputDataMessage();
        $messg->unserialize($data);
        $messg->setTransportSpecificData($transportSpecificData);
        
        return $messg;
    }
}

