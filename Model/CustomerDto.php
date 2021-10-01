<?php

namespace Outshifter\Outshifter\Model;

use Outshifter\Outshifter\Api\Data\CustomerDtoInterface;

class CustomerDto implements CustomerDtoInterface
{
  private $email;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->email = '';
  }

  /**
   * Get the email.
   *
   * @api
   * @return string.
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set the email.
   *
   * @api
   * @param $email string.
   * @return null
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }
}
