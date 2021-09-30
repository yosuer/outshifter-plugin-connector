<?php

namespace Outshifter\Outshifter\Api;

interface OutshifterApiService
{

  /**
   * POST order in magento store
   * 
   * @api
   * @param Outshifter\Outshifter\Api\Data\OrderDtoInterface $orderDto The orderDto
   * @return string The orderId
   */
  public function saveOrder($orderDto);

  /**
   * GET currency magento store
   * @return string
   */
  public function getCurrency();
}
