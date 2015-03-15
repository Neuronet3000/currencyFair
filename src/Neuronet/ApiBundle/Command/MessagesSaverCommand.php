<?php
namespace Neuronet\ApiBundle\Command;

use Neuronet\ApiBundle\Command\Worker\QueueWorkerAbstract;
use Neuronet\ApiBundle\DataQueue\QueueMessage;
use Neuronet\ApiBundle\Entity\ApiTransaction;

class MessagesSaverCommand extends QueueWorkerAbstract
{
    protected $entityManager;
    protected function configure()
    {
        $this
            ->setName('internalData:messagesSaver')
            ->setDescription('Save messages to DB')
        ;
        parent::configure();
    }
    
    protected function onWorkerStart()
    {
        parent::onWorkerStart();
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }
    
    protected function processMessage(QueueMessage $message)
    {
        //save message data here
        $entity = new ApiTransaction();
        $entity->setUserId($message->userId);
        $entity->setCurrencyFrom($message->currencyFrom);
        $entity->setCurrencyTo($message->currencyTo);
        $entity->setAmountSell($message->amountSell);
        $entity->setAmountBuy($message->amountBuy);
        $entity->setRate($message->rate);
        $entity->setOriginatingCountry($message->originatingCountry);
        $entity->setTimePlaced($message->timePlaced);
        
        $this->entityManager->persist($entity);
        
        return true;
    }
    
    protected function onChunkEnd() 
    {
        $this->entityManager->flush();
    }
}

