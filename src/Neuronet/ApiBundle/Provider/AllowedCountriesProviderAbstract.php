<?php
namespace Neuronet\ApiBundle\Provider;

abstract class AllowedCountriesProviderAbstract
{
    public function getAllowedCountriesList()
    {
        return [];
    }
    
    public function isCountyAllowed($country)
    {
        return false;
    }
}

