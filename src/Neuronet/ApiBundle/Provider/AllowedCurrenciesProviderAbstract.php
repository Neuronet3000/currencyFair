<?php
namespace Neuronet\ApiBundle\Provider;

abstract class AllowedCurrenciesProviderAbstract
{
    public function getAllowedCurrenciesList()
    {
        return [];
    }
    
    public function isCurrencyAllowed($currency)
    {
        return false;
    }
}
