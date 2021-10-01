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
}
