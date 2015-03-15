<?php
namespace Neuronet\ApiBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Neuronet\ApiBundle\Command\Worker\QueueWorkerAbstract;
use Neuronet\ApiBundle\DataQueue\DataQueueAbstract;
use Neuronet\ApiBundle\DataQueue\QueueMessage;
use Neuronet\ApiBundle\Entity\CurrencyPairCounters;

class TransactionCountersByPairCommand extends QueueWorkerAbstract
{
    protected $queuePrefix;
    protected $currecyFrom;
    protected $currecyTo;
    protected $countersByCountry;
    protected $entityManager = [];
    
    public function __construct(DataQueueAbstract $inputDataQueue, $queuePrefix)
    {
        $this->queuePrefix = $queuePrefix;
        parent::__construct($inputDataQueue);
    }
    
    protected function configure()
    {
        $this
            ->setName('internalData:countersByPair')
            ->setDescription('Calculate counters by currency pairs')
            ->addArgument("currencyFrom", InputArgument::REQUIRED, "Currency from")
            ->addArgument("currencyTo", InputArgument::REQUIRED, "Currency to")
        ;
        parent::configure();
    }
    
    protected function onWorkerStart()
    {
        $this->currecyFrom = (string)($this->input->getArgument('currencyFrom'));
        $this->currecyTo = (string)($this->input->getArgument('currencyTo'));
        $queueName = $this->queuePrefix.'_'.$this->currecyFrom.'_'.$this->currecyTo;
        $this->inputDataQueue->queueName = $queueName;
        $this->inputDataQueue->routingKey = $this->currecyFrom.'.'.$this->currecyTo.'.#';
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
        parent::onWorkerStart();
    }
    
    protected function processMessage(QueueMessage $message)
    {
        //process message here
        $originatingCountry = $message->originatingCountry;
        
        if (!isset($this->countersByCountry[$originatingCountry])) {
            
            $repository = $this->entityManager->getRepository('NeuronetApiBundle:CurrencyPairCounters');
            
            $query = $repository->createQueryBuilder('c')
                    ->where('c.currencyFrom = :currencyFrom AND c.currencyTo = :currencyTo AND c.originatingCountry = :originatingCountry')
                    ->setParameter('currencyFrom', $this->currecyFrom)
                    ->setParameter('currencyTo', $this->currecyTo)
                    ->setParameter('originatingCountry', $originatingCountry)
                    ->getQuery();
            
            $counters = $query->getResult();
            
            if (empty($counters)) {
                $currencyCounters = new CurrencyPairCounters();
                $currencyCounters->setCurrencyFrom($this->currecyFrom);
                $currencyCounters->setCurrencyTo($this->currecyTo);
                $currencyCounters->setOriginatingCountry($originatingCountry);
                $currencyCounters->setTotalBuy(0.0);
                $currencyCounters->setTotalSell(0.0);
                $currencyCounters->setTransactionsCnt(0);
            } else {
                $currencyCounters = reset($counters);
            }
            
            unset($counters);
            
            $this->countersByCountry[$originatingCountry] = $currencyCounters;
        } else {
            $currencyCounters = $this->countersByCountry[$originatingCountry];
        }
        
        $currencyCounters->incTotalSell($message->amountSell);
        $currencyCounters->incTotalBuy($message->amountBuy);
        $currencyCounters->incTransactionsCnt();
        $this->entityManager->persist($currencyCounters);
        
        return true;
    }
    
    protected function onChunkEnd() 
    {
        $this->entityManager->flush();
    }
}

