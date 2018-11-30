<?php

namespace Divante\Walkthechat\Model;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class ProductService
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * ProductService constructor.
     *
     * @param \Magento\Catalog\Model\ProductRepository     $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get all products available for export
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getAllForExport()
    {
        $configurableProductsSearchCriteria = $this
            ->searchCriteriaBuilder
            ->addFilter('type_id', \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE)
            ->create();

        $configurableProducts = $this->productRepository->getList($configurableProductsSearchCriteria);

        $ignoreSimpleIds = [];

        foreach ($configurableProducts->getItems() as $configurableProduct) {
            foreach ($configurableProduct->getTypeInstance()->getUsedProducts($configurableProduct) as $child) {
                $ignoreSimpleIds[] = $child->getId();
            }
        }

        array_unique($ignoreSimpleIds);

        $simpleProductsSearchCriteria = $this
            ->searchCriteriaBuilder
            ->addFilter('type_id', \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->addFilter('entity_id', $ignoreSimpleIds, 'nin')
            ->create();

        $simpleProducts = $this->productRepository->getList($simpleProductsSearchCriteria);

        return array_merge($simpleProducts->getItems(), $configurableProducts->getItems());
    }
}
