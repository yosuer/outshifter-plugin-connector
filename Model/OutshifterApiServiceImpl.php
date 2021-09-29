<?php

namespace Outshifter\Outshifter\Model;

class OutshifterApiServiceImpl
{

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
      $response = [
        'currency' => 'EUR',
      ];
    } catch (\Exception $e) {
      $response = ['error' => $e->getMessage()];
    }

    return json_encode($response);
  }
}
