<?php

namespace Outshifter\Outshifter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Outshifter\Outshifter\Helper\Utils;
use Outshifter\Outshifter\Logger\Logger;

class OutshifterService extends AbstractHelper
{
    /**
     * @var Utils
     */
    protected $utils;

    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        Utils $utils,
        Logger $logger)
    {
        $this->utils = $utils;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    public function saveProduct($product, $apiKey, $currency)
    {
        $result = array(
          'success' => true,
        );
        $productType = $product->getTypeId();
        $productId = $product->getId();
        if ($productType !== 'simple' && $productType !== 'configurable') {
          $this->_logger->info('[OutshifterService.saveProduct] skipping product '.$productId.' (type='.$productType.').');
          $result['success'] = false;
        } else {
          $this->_logger->info('[OutshifterService.saveProduct] exporting product '.$productId.' (type='.$productType.')');
          $quantity = $this->utils->getQuantity($product);
          $productImages = $product->getMediaGalleryImages();
          $images = array();
          foreach($productImages as $key => $image) {
            $b64image = base64_encode(file_get_contents($image->getUrl()));
            $images[] = array('order' => $key, "image" => 'data:image/jpg;base64,'.$b64image);
          }
          $optionsEnabled = $productType === 'configurable';
          $variants = array();
          $options = array();
          if ($optionsEnabled) {
              $available_variations = $product->getTypeInstance()->getUsedProducts($product);
              $attributes = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
              $quantity = 0;
              $orderOption = 1;
              foreach ($attributes as $attribute) {
                $strOptions = '';
                foreach ($attribute['values'] as $option) {
                  $strOptions = $strOptions . (($strOptions == '') ? $option['label'] : ',' . $option['label']);
                }
                $options[] = array(
                  "name" => $attribute['label'],
                  "order" => $orderOption,
                  "values" => $strOptions
                );
                $orderOption++;
              }
              foreach ($available_variations as $variation) {
                $quantityVariant = $this->utils->getQuantity($variation);
                $quantity = $quantity + $quantityVariant;
                $title = '';
                foreach ($attributes as $attribute) {
                  $optionId = $variation->getData($attribute['attribute_code']);
                  if (null !== $optionId) {
                    $key = array_search($optionId, array_column($attribute['values'], 'value_index'));
                    if ($key !== false) {
                      $value = $attribute['values'][$key]['label'];
                      $title = $title === '' ? $value : $title.'-'.$value;
                    }
                  }
                }
                $variants[] = array(
                  "sku" => $variation->getSku(),
                  "price" => $variation->getPrice(),
                  "quantity" => $quantityVariant,
                  "title" => $title,
                  "originId" => $variation->getId(),
                  "barcode" => ""
                );
              }
          }
  
          $postData = array(
            'title' => $product->getName(),
            "description" => $product->getDescription(),
            "publicPrice" => array(
              "amount" => $product->getPrice(),
              "currency" => $currency
            ),
            'origin' => 'MAGENTO',
            'originId' => $productId,
            "images" => $images,
            "quantity" => $quantity,
            "barcode" => "",
            'sku' => $product->getSku(),
            "optionsEnabled" => $optionsEnabled,
            "options" => $options,
            "variants" => $variants,
            "weight" => $product->getWeight(),
            "currency" => $currency
          );
  
          $ch = curl_init('https://03d1-186-22-17-73.ngrok.io/magento/products');
          curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'authorization: '.$apiKey,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
          ));
          $response = curl_exec($ch);
          if($response === FALSE) {
            $result['message'] = 'Connection problem exporting product %1, try again in a moment';
            $result['success'] = false;
          }
          if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 401) {
            $result['message'] = 'Please review your outshifter api key in Stores -> Configuration -> Outshifter';
            $result['success'] = false;
          }
  
          curl_close($ch);
        }
        return $result;

    }
}