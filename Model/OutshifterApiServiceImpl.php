<?php

namespace Outshifter\Outshifter\Model;

use Outshifter\Outshifter\Helper\Utils;
use Outshifter\Outshifter\Logger\Logger;

class OutshifterApiServiceImpl
{

  /**
   * @var Utils
   */
  protected $utils;

  public function __construct(
    Utils $utils,
    Logger $logger
  ) {
    $this->utils = $utils;
    $this->_logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function saveOrder()
  {
    return 'save order';
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency()
  {
    try {
      $response = $this->utils->getCurrencyStore();
    } catch (\Exception $e) {
      $response = ['error' => $e->getMessage()];
    }

    return json_encode($response);
  }
}
