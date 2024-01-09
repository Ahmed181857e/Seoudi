<?php
// File: app/code/Seoudi/CustomRewards/Block/ProductShare.php

namespace Seoudi\CustomRewards\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ProductShare extends Template
{
    protected $productRepository;
    protected $registry;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->registry = $registry;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getProductUrl()
    {
        $productId = $this->getCurrentProductId();
        if (!$productId) {
            return null;
        }

        try {
            $product = $this->productRepository->getById($productId);
            return $product->getProductUrl();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            // Handle exception (product not found)
            return null;
        }
    }

    public function getFacebookAppId()
    {
        // Retrieve custom configuration value
        return $this->scopeConfig->getValue('seoudi/customlogin/Facebookid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function getCurrentProductId()
    {
        $product = $this->registry->registry('current_product');
        return $product ? $product->getId() : null;
    }
}
