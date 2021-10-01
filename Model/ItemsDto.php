<?php

namespace Outshifter\Outshifter\Model;

use Outshifter\Outshifter\Api\Data\ItemsDtoInterface;

class ItemsDto implements ItemsDtoInterface
{
  private $quantity;
  private $productId;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->quantity = 0;
  }

  /**
   * Get the quantity.
   *
   * @api
   * @return int.
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Set the quantity.
   *
   * @api
   * @param $quantity int.
   * @return null
   */
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
  }

  /**
   * Get the productId.
   *
   * @api
   * @return int.
   */
  public function getProductId()
  {
    return $this->productId;
  }

  /**
   * Set the productId.
   *
   * @api
   * @param $productId int.
   * @return null
   */
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }
}
