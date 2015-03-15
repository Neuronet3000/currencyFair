<?php
namespace Neuronet\ApiBundle\Command;

use Neuronet\ApiBundle\DataQueue\DataQueueAbstract;
use Neuronet\ApiBundle\DataQueue\QueueMessage;
use Neuronet\ApiBundle\Command\Worker\QueueWorkerAbstract;
use Neuronet\ApiBundle\Provider\AllowedCountriesProviderAbstract;
use Neuronet\ApiBundle\Provider\AllowedCurrenciesProviderAbstract;

class InputDataProcessorCommand extends QueueWorkerAbstract {
    protected $outputDataQueue;
    protected $allowedCountriesProvider;
    protected $allowedCurrienciesProvider;
    protected $chunkSize = 1;
    protected $sleepPeriod = 1;
    
    public function __construct(
            DataQueueAbstract $inputDataQueue, 
            DataQueueAbstract $outputDataQueue, 
            AllowedCountriesProviderAbstract $allowedCountriesProvider,
            AllowedCurrenciesProviderAbstract $allowedCurrenciesProvider
    )
    {
        $this->outputDataQueue = $outputDataQueue;
        $this->allowedCountriesProvider = $allowedCountriesProvider;
        $this->allowedCurrienciesProvider = $allowedCurrenciesProvider;
        parent::__construct($inputDataQueue);
    }
    
    protected function configure()
    {
        $this
            ->setName('api:processInput')
            ->setDescription('Process input data messages')
        ;
        parent::configure();
    }
    
    protected function processMessage(QueueMessage $message)
    {
        $isCurrencyFromAllowed = $this->allowedCurrienciesProvider->isCurrencyAllowed($message->currencyFrom);
        $isCurrencyToAllowed = $this->allowedCurrienciesProvider->isCurrencyAllowed($message->currencyTo);
        $isOriginatingCountryAllowed = $this->allowedCountriesProvider->isCountyAllowed($message->originatingCountry);

        $messageValid = false;

        if ($isCurrencyFromAllowed
            && $isCurrencyToAllowed
            && $isOriginatingCountryAllowed
        )
        {
            $messageValid = true;
        }

        $transactionTime = strtotime($message->timePlaced);
        if ($transactionTime !== false) {
            $messageValid = true;
            $message->timePlaced = $transactionTime;
        }

        if ($messageValid) {
            //process message here
            $routingKey = $message->currencyFrom . '.' . $message->currencyTo . '.' . $message->originatingCountry;
            $this->outputDataQueue->routingKey = $routingKey;

            $this->outputDataQueue->putMessageToQueue($message);
        }
        
        return true;
    }
    
}

