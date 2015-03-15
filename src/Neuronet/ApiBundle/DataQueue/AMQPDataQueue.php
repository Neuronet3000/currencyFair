<?php
namespace Neuronet\ApiBundle\DataQueue;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPDataQueue extends DataQueueAbstract {
    
    protected $connection;
    protected $channel;
    protected $exchangeName;
    protected $exchangeCreated = false;
    protected $queueCreated = false;
    public $routingKey = '';
    public $queueName;
    public $exchangeType = 'direct';
    public $exchangeDurable = true;
    public $exchangeAutoDelete = false;
    public $queueDurable = true;
    public $queueExclusive = false;
    public $queueAutoDelete = false;
    
    public function __construct($host, $port, $user, $pass, $vhost, $exchange)
    {
        $this->connection = new AMQPConnection($host, $port, $user, $pass, $vhost);
        $this->channel = $this->connection->channel();
        $this->exchangeName = $exchange;
    }
    
    public function setRoutingKey($routingKey) 
    {
        $this->routingKey = $routingKey;
    }
    
    public function setQueueName($queueName) 
    {
        $this->queueName = $queueName;
    }
    
    public function putMessageToQueue(QueueMessage $message) 
    {
        $payload = $message->serialize();
        $msg = new AMQPMessage($payload, array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $this->channel->basic_publish($msg, $this->exchangeName, $this->routingKey);
    }
    
    public function getMessageFromQueue() 
    {
        if (!$this->exchangeCreated) {
            $passive=false;
            $this->channel->exchange_declare(
                $this->exchangeName,
                $this->exchangeType,
                $passive,
                $this->exchangeDurable,
                $this->exchangeAutoDelete
            );
            $this->exchangeCreated = true;
        }
        if (!$this->queueCreated) {
            $passive=false;
            $this->channel->queue_declare(
                $this->queueName,
                $passive,
                $this->queueDurable,
                $this->queueExclusive,
                $this->queueAutoDelete
            );
            $this->channel->queue_bind($this->queueName, $this->exchangeName, $this->routingKey);
            $this->queueCreated = true;
        }
        
        $msgFromQueue = $this->channel->basic_get($this->queueName);
        if (!isset($msgFromQueue)) {
            return null;
        }
        return $this->dataMessageFactory->getQueueMessage($msgFromQueue->body, $msgFromQueue);
    }
    
    public function markMessageDone(QueueMessage $message) 
    {
        $transportSpecificData = $message->getTransportSpecificData();
        $this->channel->basic_ack($transportSpecificData->delivery_info['delivery_tag']);
    }
    
    public function markMessageFail(QueueMessage $message)
    {
        $transportSpecificData = $message->getTransportSpecificData();
        $$this->channel->basic_nack($transportSpecificData->delivery_info['delivery_tag']);
    }
}
