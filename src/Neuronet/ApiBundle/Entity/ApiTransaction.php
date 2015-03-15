<?php
namespace Neuronet\ApiBundle\Entity;
class ApiTransaction
{
    private $userId;
    private $currencyFrom;
    private $currencyTo;
    private $amountSell;
    private $amountBuy;
    private $rate;
    private $timePlaced;
    private $originatingCountry;
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
     * Set userId
     *
     * @param string $userId
     * @return ApiTransaction
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set currencyFrom
     *
     * @param string $currencyFrom
     * @return ApiTransaction
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
     * @return ApiTransaction
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
     * Set amountSell
     *
     * @param float $amountSell
     * @return ApiTransaction
     */
    public function setAmountSell($amountSell)
    {
        $this->amountSell = $amountSell;

        return $this;
    }

    /**
     * Get amountSell
     *
     * @return float 
     */
    public function getAmountSell()
    {
        return $this->amountSell;
    }

    /**
     * Set amountBuy
     *
     * @param float $amountBuy
     * @return ApiTransaction
     */
    public function setAmountBuy($amountBuy)
    {
        $this->amountBuy = $amountBuy;

        return $this;
    }

    /**
     * Get amountBuy
     *
     * @return float 
     */
    public function getAmountBuy()
    {
        return $this->amountBuy;
    }

    /**
     * Set rate
     *
     * @param float $rate
     * @return ApiTransaction
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set originatingCountry
     *
     * @param string $originatingCountry
     * @return ApiTransaction
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
     * Set timePlaced
     *
     * @param \DateTime $timePlaced
     * @return ApiTransaction
     */
    public function setTimePlaced($timePlaced)
    {
        $this->timePlaced = new \DateTime();
        $this->timePlaced->setTimestamp($timePlaced);
        return $this;
    }

    /**
     * Get timePlaced
     *
     * @return \DateTime 
     */
    public function getTimePlaced()
    {
        return $this->timePlaced;
    }
}
