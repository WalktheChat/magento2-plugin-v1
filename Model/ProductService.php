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
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroup
     */
    protected $filterGroup;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * ProductService constructor.
     *
     * @param \Magento\Catalog\Model\ProductRepository       $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param \Magento\Framework\Api\Search\FilterGroup      $filterGroup
     * @param \Magento\Framework\Api\FilterBuilder           $filterBuilder
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\FilterBuilder $filterBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteria    = $searchCriteria;
        $this->filterGroup       = $filterGroup;
        $this->filterBuilder     = $filterBuilder;
    }

    /**
     * Get all products available for export
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getAllForExport()
    {
        $this->filterGroup->setFilters([
            $this->filterBuilder
                ->setField('type_id')
                ->setConditionType('in')
                ->setValue([
                    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
                ])
                ->create(),
            $this->filterBuilder
                ->setField('walkthechat_id')
                ->setConditionType('null')
                ->create(),
        ]);

        $this->searchCriteria->setFilterGroups([$this->filterGroup]);
        $products = $this->productRepository->getList($this->searchCriteria);

        return $products->getItems();
    }
}
