<?php
namespace Neuronet\ApiBundle\DataQueue;

class QueueMessage {
    protected $payload;
    protected $transportSpecificData;
    
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }
    
    public function setTransportSpecificData($transportSpecificData)
    {
        $this->transportSpecificData = $transportSpecificData;
    }
    public function getTransportSpecificData()
    {
        return $this->transportSpecificData;
    }
    
    public function serialize() 
    {
        return serialize($this->payload);
    }
    
    public function unserialize($data) 
    {
        $this->payload = unserialize($data);
    }
}

