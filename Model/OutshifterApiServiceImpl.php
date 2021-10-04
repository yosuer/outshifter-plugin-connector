<?php

namespace Outshifter\Outshifter\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Outshifter\Outshifter\Api\OutshifterApiService;
use Outshifter\Outshifter\Helper\Utils;
use Outshifter\Outshifter\Logger\Logger;

class OutshifterApiServiceImpl implements OutshifterApiService
{

  /**
   * @var StoreManagerInterface
   */
  protected $storeManager;

  /**
   * @var CustomerFactory
   */
  protected $customerFactory;

  /**
   * @var QuoteFactory
   */
  protected $quoteFactory;

  /**
   * @var Product
   */
  protected $productModel;

  /**
   * @var CustomerRepositoryInterface
   */
  protected $customerRepository;

  /**
   * @var QuoteManagement
   */
  protected $quoteManagement;

  /**
   * @var CartRepositoryInterface
   */
  protected $cartRepository;

  /**
   * @var CartManagementInterface
   */
  protected $cartManagement;

  /**
   * @var Utils
   */
  protected $utils;

  /**
   * @var Logger
   */
  protected $_logger;

  public function __construct(
    StoreManagerInterface $storeManager,
    CustomerFactory $customerFactory,
    QuoteFactory $quoteFactory,
    CustomerRepositoryInterface $customerRepository,
    Product $productModel,
    QuoteManagement $quoteManagement,
    CartRepositoryInterface $cartRepository,
    CartManagementInterface $cartManagement,
    Utils $utils,
    Logger $logger
  ) {
    $this->storeManager = $storeManager;
    $this->customerFactory = $customerFactory;
    $this->quoteFactory = $quoteFactory;
    $this->customerRepository = $customerRepository;
    $this->productModel = $productModel;
    $this->quoteManagement = $quoteManagement;
    $this->cartRepository = $cartRepository;
    $this->cartManagement = $cartManagement;
    $this->utils = $utils;
    $this->_logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function saveOrder($customerDto, $itemsDto)
  {
    $billingAddress = [
      'firstname'    => $customerDto->getBillingName(),
      'lastname'     => '-',
      'street' => $customerDto->getBillingStreet(),
      'city' => $customerDto->getBillingCity(),
      'region' => $customerDto->getBillingRegion(),
      'country_id' => $customerDto->getBillingCountry(),
      'postcode' => $customerDto->getBillingZip(),
      'telephone' => $customerDto->getBillingPhone(),
      'fax' => $customerDto->getBillingPhone(),
      'save_in_address_book' => 0
    ];
    $shippingAddress = [
      'firstname'    => $customerDto->getShippingName(),
      'lastname'     => '-',
      'street' => $customerDto->getShippingStreet(),
      'city' => $customerDto->getShippingCity(),
      'region' => $customerDto->getShippingRegion(),
      'country_id' => $customerDto->getShippingCountry(),
      'postcode' => $customerDto->getShippingZip(),
      'telephone' => $customerDto->getShippingPhone(),
      'fax' => $customerDto->getShippingPhone(),
      'save_in_address_book' => 0
    ];
    $this->_logger->info('[OutshifterApi.saveOrder] Creating order to custmer ' . $customerDto->getEmail());
    $store = $this->storeManager->getStore();
    $websiteId = $this->storeManager->getStore()->getWebsiteId();
    $customer = $this->customerFactory->create();
    $customer->setWebsiteId($websiteId);
    $customer->loadByEmail($customerDto->getEmail());
    if (!$customer->getEntityId()) {
      //If not avilable then create this customer 
      $customer->setWebsiteId($websiteId)
        ->setStore($store)
        ->setFirstname($customerDto->getBillingName())
        ->setLastname("-")
        ->setEmail($customerDto->getEmail())
        ->setPassword($customerDto->getEmail());
      $customer->save();
    }

    $this->_logger->info('[OutshifterApi.saveOrder] Customer created');

    $cartId = $this->cartManagement->createEmptyCart();
    $cart = $this->cartRepository->get($cartId);
    $cart->setStore($store);
    $customer = $this->customerRepository->getById($customer->getEntityId());
    $cart->setCurrency();
    $cart->assignCustomer($customer);

    foreach ($itemsDto as $item) {
      $this->_logger->info('[OutshifterApi.saveOrder] product ' . $item->getProductId() . ', quantity ' . $item->getQuantity());
      $product = $this->productModel->load($item->getProductId());
      $cart->addProduct(
        $product,
        intval($item->getQuantity())
      );
    }

    $this->_logger->info('[OutshifterApi.saveOrder] Product added');

    $cart->getBillingAddress()->addData($billingAddress);
    $cart->getShippingAddress()->addData($shippingAddress);
    $cart->getShippingAddress()->setCollectShippingRates(true);
    $cart->getShippingAddress()->setShippingMethod('freeshipping_freeshipping');

    $this->_logger->info('[OutshifterApi.saveOrder] Shipping method setted');

    $cart->setPaymentMethod('checkmo'); //payment method
    $cart->setInventoryProcessed(false); //not effetc inventory

    $this->_logger->info('[OutshifterApi.saveOrder] Payment method setted');

    // Set Sales Order Payment
    $cart->getPayment()->importData(['method' => 'checkmo']);

    // Collect Totals & Save Quote
    $cart->collectTotals();

    $this->_logger->info('[OutshifterApi.saveOrder] Cart prepared');

    // Create Order From Quote
    $cart->save();

    $cart = $this->cartRepository->get($cart->getId());

    $this->_logger->info('[OutshifterApi.saveOrder] Cart ' . $cart->getId() . ' saved');

    $cartCustomer = $cart->getCustomer();

    $this->_logger->info('[OutshifterApi.saveOrder] CartCustomer: ' . json_encode($cartCustomer));

    $orderId = $this->cartManagement->placeOrder($cart->getId());

    $this->_logger->info('[OutshifterApi.saveOrder] Order ' . $orderId . ' created');

    return $orderId;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency()
  {
    try {
      $response = $this->utils->getCurrencyStore();
    } catch (\Exception $e) {
      $response = $e->getMessage();
    }

    return $response;
  }
}
