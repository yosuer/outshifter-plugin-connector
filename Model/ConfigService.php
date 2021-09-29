<?php

namespace Outshifter\Outshifter\Model;

class ConfigService
{

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
