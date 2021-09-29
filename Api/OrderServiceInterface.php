<?php

namespace Outshifter\Outshifter\Api;

interface OrderServiceInterface
{

  /**
   * POST order in magento store
   * @return string
   */
  public function save($param);
}
