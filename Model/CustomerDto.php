<?php

namespace Outshifter\Outshifter\Model;

use Outshifter\Outshifter\Api\Data\CustomerDtoInterface;

class CustomerDto implements CustomerDtoInterface
{
  private $email;
  private $billingName;
  private $billingStreet;
  private $billingCity;
  private $billingRegion;
  private $billingCountry;
  private $billingZip;
  private $billingPhone;
  private $shippingName;
  private $shippingStreet;
  private $shippingCity;
  private $shippingRegion;
  private $shippingCountry;
  private $shippingZip;
  private $shippingPhone;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->email = '';
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set
   *
   * @api
   * @param $email string.
   * @return null
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingName()
  {
    return $this->billingName;
  }

  /**
   * Set
   *
   * @api
   * @param $billingName string.
   * @return null
   */
  public function setBillingName($billingName)
  {
    $this->billingName = $billingName;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingStreet()
  {
    return $this->billingStreet;
  }

  /**
   * Set
   *
   * @api
   * @param $billingStreet string.
   * @return null
   */
  public function setBillingStreet($billingStreet)
  {
    $this->billingStreet = $billingStreet;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingCity()
  {
    return $this->billingCity;
  }

  /**
   * Set
   *
   * @api
   * @param $billingCity string.
   * @return null
   */
  public function setBillingCity($billingCity)
  {
    $this->billingCity = $billingCity;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingRegion()
  {
    return $this->billingRegion;
  }

  /**
   * Set
   *
   * @api
   * @param $billingRegion string.
   * @return null
   */
  public function setBillingRegion($billingRegion)
  {
    $this->billingRegion = $billingRegion;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingCountry()
  {
    return $this->billingCountry;
  }

  /**
   * Set
   *
   * @api
   * @param $billingCountry string.
   * @return null
   */
  public function setBillingCountry($billingCountry)
  {
    $this->billingCountry = $billingCountry;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingZip()
  {
    return $this->billingZip;
  }

  /**
   * Set
   *
   * @api
   * @param $billingZip string.
   * @return null
   */
  public function setBillingZip($billingZip)
  {
    $this->billingZip = $billingZip;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getBillingPhone()
  {
    return $this->billingPhone;
  }

  /**
   * Set
   *
   * @api
   * @param $billingPhone string.
   * @return null
   */
  public function setBillingPhone($billingPhone)
  {
    $this->billingPhone = $billingPhone;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingName()
  {
    return $this->shippingName;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingName string.
   * @return null
   */
  public function setShippingName($shippingName)
  {
    $this->shippingName = $shippingName;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingStreet()
  {
    return $this->shippingStreet;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingStreet string.
   * @return null
   */
  public function setShippingStreet($shippingStreet)
  {
    $this->shippingStreet = $shippingStreet;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingCity()
  {
    return $this->shippingCity;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingCity string.
   * @return null
   */
  public function setShippingCity($shippingCity)
  {
    $this->shippingCity = $shippingCity;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingRegion()
  {
    return $this->shippingRegion;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingRegion string.
   * @return null
   */
  public function setShippingRegion($shippingRegion)
  {
    $this->shippingRegion = $shippingRegion;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingCountry()
  {
    return $this->shippingCountry;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingCountry string.
   * @return null
   */
  public function setShippingCountry($shippingCountry)
  {
    $this->shippingCountry = $shippingCountry;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingPhone()
  {
    return $this->shippingPhone;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingPhone string.
   * @return null
   */
  public function setShippingPhone($shippingPhone)
  {
    $this->shippingPhone = $shippingPhone;
  }

  /**
   * Get
   *
   * @api
   * @return string.
   */
  public function getShippingZip()
  {
    return $this->shippingZip;
  }

  /**
   * Set
   *
   * @api
   * @param $shippingZip string.
   * @return null
   */
  public function setShippingZip($shippingZip)
  {
    $this->shippingZip = $shippingZip;
  }
}
