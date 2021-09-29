<?php

namespace Outshifter\Outshifter\Api;

interface ConfigServiceInterface
{

  /**
   * GET currency magento store
   * @return string
   */
  public function getCurrency($param);
}
