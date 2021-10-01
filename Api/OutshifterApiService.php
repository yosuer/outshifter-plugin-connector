<?php

namespace Outshifter\Outshifter\Api;

interface OutshifterApiService
{

  /**
   * POST order in magento store
   * 
   * @api
   * @param Outshifter\Outshifter\Api\Data\CustomerDtoInterface $customer The customer data
   * @param Outshifter\Outshifter\Api\Data\ItemsDtoInterface[] $items The items data
   * @return string The orderId
   */
  public function saveOrder($customer, $items);

  /**
   * GET currency magento store
   * @return string
   */
  public function getCurrency();
}
