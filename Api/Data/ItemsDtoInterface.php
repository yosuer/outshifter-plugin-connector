<?php

namespace Outshifter\Outshifter\Api\Data;

interface ItemsDtoInterface
{
  /**
   * Get the productId.
   *
   * @api
   * @return int
   */
  public function getProductId();

  /**
   * Set the productId.
   *
   * @api
   * @param $productId int
   * @return null
   */
  public function setProductId($productId);

  /**
   * Get the quantity.
   *
   * @api
   * @return int
   */
  public function getQuantity();

  /**
   * Set the quantity.
   *
   * @api
   * @param $quantity int
   * @return null
   */
  public function setQuantity($quantity);
}
