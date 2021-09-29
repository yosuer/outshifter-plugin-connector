<?php

namespace Outshifter\Outshifter\Api;

interface OutshifterApiService
{

  /**
   * POST order in magento store
   * @return string
   */
  public function saveOrder();

  /**
   * GET currency magento store
   * @return string
   */
  public function getCurrency();
}
