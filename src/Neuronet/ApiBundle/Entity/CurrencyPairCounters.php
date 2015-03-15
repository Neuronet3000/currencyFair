<?php
namespace Neuronet\ApiBundle\Entity;

class CurrencyPairCounters
{
    private $currencyFrom;
    private $currencyTo;
    private $originatingCountry;
    private $totalSell;
    private $totalBuy;
    private $transactionsCnt;
    /**
     * @var integer
     */
    private $id;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set currencyFrom
     *
     * @param string $currencyFrom
     * @return CurrencyPairCounters
     */
    public function setCurrencyFrom($currencyFrom)
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    /**
     * Get currencyFrom
     *
     * @return string 
     */
    public function getCurrencyFrom()
    {
        return $this->currencyFrom;
    }

    /**
     * Set currencyTo
     *
     * @param string $currencyTo
     * @return CurrencyPairCounters
     */
    public function setCurrencyTo($currencyTo)
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    /**
     * Get currencyTo
     *
     * @return string 
     */
    public function getCurrencyTo()
    {
        return $this->currencyTo;
    }

    /**
     * Set originatingCountry
     *
     * @param string $originatingCountry
     * @return CurrencyPairCounters
     */
    public function setOriginatingCountry($originatingCountry)
    {
        $this->originatingCountry = $originatingCountry;

        return $this;
    }

    /**
     * Get originatingCountry
     *
     * @return string 
     */
    public function getOriginatingCountry()
    {
        return $this->originatingCountry;
    }

    /**
     * Set totalSell
     *
     * @param float $totalSell
     * @return CurrencyPairCounters
     */
    public function setTotalSell($totalSell)
    {
        $this->totalSell = $totalSell;

        return $this;
    }
    /**
     * increment totalSell
     *
     * @param float $addSell
     * @return CurrencyPairCounters
     */
    public function incTotalSell($addSell)
    {
        $this->totalSell += $addSell;

        return $this;
    }

    /**
     * Get totalSell
     *
     * @return float 
     */
    public function getTotalSell()
    {
        return $this->totalSell;
    }

    /**
     * Set totalBuy
     *
     * @param float $totalBuy
     * @return CurrencyPairCounters
     */
    public function setTotalBuy($totalBuy)
    {
        $this->totalBuy = $totalBuy;

        return $this;
    }
    /**
     * increment totalBuy
     *
     * @param float $addBuy
     * @return CurrencyPairCounters
     */
    public function incTotalBuy($addBuy)
    {
        $this->totalBuy += $addBuy;

        return $this;
    }

    /**
     * Get totalBuy
     *
     * @return float 
     */
    public function getTotalBuy()
    {
        return $this->totalBuy;
    }

    /**
     * Set transactionsCnt
     *
     * @param integer $transactionsCnt
     * @return CurrencyPairCounters
     */
    public function setTransactionsCnt($transactionsCnt)
    {
        $this->transactionsCnt = $transactionsCnt;

        return $this;
    }
    /**
     * increment transactionsCnt
     *
     * @param integer $addCnt
     * @return CurrencyPairCounters
     */
    public function incTransactionsCnt($addCnt = 1)
    {
        $this->transactionsCnt += $addCnt;

        return $this;
    }

    /**
     * Get transactionsCnt
     *
     * @return integer 
     */
    public function getTransactionsCnt()
    {
        return $this->transactionsCnt;
    }
}
