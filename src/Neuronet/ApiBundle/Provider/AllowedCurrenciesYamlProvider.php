<?php
namespace Neuronet\ApiBundle\Provider;

use Symfony\Component\Yaml\Parser as YamlParser;

class AllowedCurrenciesYamlProvider extends AllowedCurrenciesProviderAbstract
{
    protected $yamlFilePath;
    protected $allowedCurrecies;
    public function __construct($yamlFilePath)
    {
        $this->yamlFilePath = $yamlFilePath;
    }
    
    protected function loadAlowedCurrencies()
    {
        $yamlParser = new YamlParser();
        $allowedCurrencies = $yamlParser->parse(file_get_contents($this->yamlFilePath));
        $this->allowedCurrecies = array_combine($allowedCurrencies, $allowedCurrencies);
    }
    
    public function getAllowedCurrenciesList()
    {
        if (is_null($this->allowedCurrecies)) {
            $this->loadAlowedCurrencies();
        }
        
        return $this->allowedCurrecies;
    }
    
    public function isCurrencyAllowed($currency)
    {
        if (!is_string($currency)) {
            throw new \InvalidArgumentException("currency must be a string", E_USER_ERROR);
        }
        
        if (is_null($this->allowedCurrecies)) {
            $this->loadAlowedCurrencies();
        }
        
        $isCountryAllowed = false;
        
        if (isset($this->allowedCurrecies[$currency])) {
             $isCountryAllowed = true;
        }
        
        return $isCountryAllowed;
    }
}

