<?php
namespace Neuronet\ApiBundle\DataQueue;

class ApiInputDataMessage extends QueueMessage {
    public $userId;
    public $currencyFrom;
    public $currencyTo;
    public $amountSell;
    public $amountBuy;
    public $rate;
    public $timePlaced;
    public $originatingCountry;
    
    public function initFromArray(array $arrayData)
    {
        $this->userId = (string)$arrayData['userId'];
        $this->currencyFrom = (string)$arrayData['currencyFrom'];
        $this->currencyTo = (string)$arrayData['currencyTo'];
        $this->currencyTo = (string)$arrayData['currencyTo'];
        $this->amountSell = floatval($arrayData['amountSell']);
        $this->amountBuy = floatval($arrayData['amountBuy']);
        $this->rate = floatval($arrayData['rate']);
        $this->timePlaced = (string)$arrayData['timePlaced'];
        $this->originatingCountry = (string)$arrayData['originatingCountry'];
    }
    
    public function setPayload($payload)
    {
        parent::setPayload($payload);
        $payloadData = json_decode($this->payload, true);
        $this->initFromArray($payloadData);
    }
    
    public function serialize() 
    {
        return json_encode($this);
    }
    
    public function unserialize($data) 
    {
        $arrayData = json_decode($data, true);
        $this->initFromArray($arrayData);
    }
}

