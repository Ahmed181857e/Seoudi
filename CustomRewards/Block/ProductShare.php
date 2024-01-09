<?php
/**
 * Date :  1/7/24, 11:31 AM
 * Author : AHMED EHAB <sahmedehab@gmail.com>
 */
namespace Seoudi\CustomRewards\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ProductShare extends Template
{
    protected $productRepository;
    protected $registry;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->registry = $registry;
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

    protected function getCurrentProductId()
    {
        $product = $this->registry->registry('current_product');
        return $product ? $product->getId() : null;
    }
}
