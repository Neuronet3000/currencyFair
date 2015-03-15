<?php
namespace Neuronet\ApiBundle\Provider;

use Symfony\Component\Yaml\Parser as YamlParser;

class AllowedCountriesYamlProvider extends AllowedCountriesProviderAbstract
{
    protected $yamlFilePath;
    protected $allowedCountries;
    public function __construct($yamlFilePath)
    {
        $this->yamlFilePath = realpath($yamlFilePath);
    }
    
    protected function loadAllowedCountries()
    {
        $yamlParser = new YamlParser();
        $allowedCountries = $yamlParser->parse(file_get_contents($this->yamlFilePath));
        $this->allowedCountries = array_combine($allowedCountries, $allowedCountries);
    }
    
    public function isCountyAllowed($country)
    {
        if (!is_string($country)) {
            throw new \InvalidArgumentException("country must be a string", E_USER_ERROR);
        }
        
        if (is_null($this->allowedCountries)) {
            $this->loadAllowedCountries();
        }
        
        $isCountryAllowed = false;
        
        if (isset($this->allowedCountries[$country])) {
             $isCountryAllowed = true;
        }
        
        return $isCountryAllowed;
    }
    
    public function getAllowedCountriesList()
    {
        if (is_null($this->allowedCountries)) {
            $this->loadAllowedCountries();
        }
        
        return $this->allowedCountries;
    }
}

