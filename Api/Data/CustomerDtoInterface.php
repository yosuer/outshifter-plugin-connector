<?php

namespace Outshifter\Outshifter\Api\Data;

interface CustomerDtoInterface
{
  /**
   * Get the email.
   *
   * @api
   * @return string
   */
  public function getEmail();

  /**
   * Set the email.
   *
   * @api
   * @param $email string
   * @return null
   */
  public function setEmail($email);

  /**
   * Get the billingName.
   *
   * @api
   * @return string
   */
  public function getBillingName();

  /**
   * Set the billingName.
   *
   * @api
   * @param $billingName string
   * @return null
   */
  public function setBillingName($billingName);

  /**
   * Get the billingStreet.
   *
   * @api
   * @return string
   */
  public function getBillingStreet();

  /**
   * Set the billingStreet.
   *
   * @api
   * @param $billingStreet string
   * @return null
   */
  public function setBillingStreet($billingStreet);

  /**
   * Get the billingCity.
   *
   * @api
   * @return string
   */
  public function getBillingCity();

  /**
   * Set the billingCity.
   *
   * @api
   * @param $billingCity string
   * @return null
   */
  public function setBillingCity($billingCity);

  /**
   * Get the billingRegion.
   *
   * @api
   * @return string
   */
  public function getBillingRegion();

  /**
   * Set the billingRegion.
   *
   * @api
   * @param $billingRegion string
   * @return null
   */
  public function setBillingRegion($billingRegion);

  /**
   * Get the billingCountry.
   *
   * @api
   * @return string
   */
  public function getBillingCountry();

  /**
   * Set the billingCountry.
   *
   * @api
   * @param $billingCountry string
   * @return null
   */
  public function setBillingCountry($billingCountry);

  /**
   * Get the billingZip.
   *
   * @api
   * @return string
   */
  public function getBillingZip();

  /**
   * Set the billingZip.
   *
   * @api
   * @param $billingZip string
   * @return null
   */
  public function setBillingZip($billingZip);

  /**
   * Get the billingPhone.
   *
   * @api
   * @return string
   */
  public function getBillingPhone();

  /**
   * Set the billingPhone.
   *
   * @api
   * @param $billingPhone string
   * @return null
   */
  public function setBillingPhone($billingPhone);

  /**
   * Get the shippingName.
   *
   * @api
   * @return string
   */
  public function getShippingName();

  /**
   * Set the shippingName.
   *
   * @api
   * @param $shippingName string
   * @return null
   */
  public function setShippingName($shippingName);

  /**
   * Get the shippingStreet.
   *
   * @api
   * @return string
   */
  public function getShippingStreet();

  /**
   * Set the shippingStreet.
   *
   * @api
   * @param $shippingStreet string
   * @return null
   */
  public function setShippingStreet($shippingStreet);

  /**
   * Get the shippingCity.
   *
   * @api
   * @return string
   */
  public function getShippingCity();

  /**
   * Set the shippingCity.
   *
   * @api
   * @param $shippingCity string
   * @return null
   */
  public function setShippingCity($shippingCity);

  /**
   * Get the shippingRegion.
   *
   * @api
   * @return string
   */
  public function getShippingRegion();

  /**
   * Set the shippingRegion.
   *
   * @api
   * @param $shippingRegion string
   * @return null
   */
  public function setShippingRegion($shippingRegion);

  /**
   * Get the shippingCountry.
   *
   * @api
   * @return string
   */
  public function getShippingCountry();

  /**
   * Set the shippingCountry.
   *
   * @api
   * @param $shippingCountry string
   * @return null
   */
  public function setShippingCountry($shippingCountry);

  /**
   * Get the shippingZip.
   *
   * @api
   * @return string
   */
  public function getShippingZip();

  /**
   * Set the shippingZip.
   *
   * @api
   * @param $shippingZip string
   * @return null
   */
  public function setShippingZip($shippingZip);

  /**
   * Get the shippingPhone.
   *
   * @api
   * @return string
   */
  public function getShippingPhone();

  /**
   * Set the shippingPhone.
   *
   * @api
   * @param $shippingPhone string
   * @return null
   */
  public function setShippingPhone($shippingPhone);
}
