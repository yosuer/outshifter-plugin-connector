<?php

namespace Outshifter\Outshifter\Api\Data;

interface OrderDtoInterface
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
}
