<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class ProductService
 *
 * @package Divante\Walkthechat\Model
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
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $stockItemRepository;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * ProductService constructor.
     *
     * @param \Magento\Catalog\Model\ProductRepository                  $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder              $searchCriteriaBuilder
     * @param \Divante\Walkthechat\Helper\Data                          $helper
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->stockItemRepository   = $stockItemRepository;
        $this->helper                = $helper;
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
            ->addFilter(
                'type_id',
                [
                    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                ],
                'in'
            )
            ->addFilter('entity_id', $ignoreSimpleIds, 'nin')
            ->create();

        $simpleProducts = $this->productRepository->getList($simpleProductsSearchCriteria);

        return array_merge($simpleProducts->getItems(), $configurableProducts->getItems());
    }

    /**
     * Prepare product data for API
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool                           $isNew
     * @param array                          $imagesData
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareProductData($product, $isNew = true, array $imagesData = [])
    {
        $mainPrice        = $this->helper->convertPrice($product->getPrice());
        $mainSpecialPrice = $this->helper->convertPrice($product->getSpecialPrice());

        $data = [
            'manageInventory'       => true,
            'visibility'            => $product->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
            'displayPrice'          => $mainPrice,
            'displayCompareAtPrice' => $mainSpecialPrice,
            'images'                => $imagesData['main'] ?? [],
            'variants'              => [
                [
                    'id'                => $product->getId(),
                    'inventoryQuantity' => $this->stockItemRepository->get($product->getId())->getQty(),
                    'weight'            => $product->getWeight(),
                    'requiresShipping'  => true,
                    'sku'               => $product->getSku(),
                    'price'             => $mainPrice,
                    'compareAtPrice'    => $mainSpecialPrice,
                    'visibility'        => $product->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
                    'taxable'           => (bool)$product->getTaxClassId(),
                ],
            ],
        ];

        // if is "update" action - don't update the title and description
        if ($isNew) {
            $data['title'] = [
                'en' => $product->getName(),
            ];

            $data['bodyHtml'] = [
                'en' => $product->getDescription(),
            ];

            $data['variants'][0]['title'] = [
                'en' => $product->getName(),
            ];
        }

        if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $configurableOptions = $product->getTypeInstance()->getConfigurableOptions($product);

            $data['variantOptions'] = [];

            // send all available options in configurable product
            foreach ($configurableOptions as $option) {
                foreach ($option as $variation) {
                    $data['variantOptions'][] = $variation['attribute_code'];

                    break;
                }
            }

            /** @var \Magento\Catalog\Model\Product[] $children */
            $children = $product->getTypeInstance()->getUsedProducts($product);

            if ($children) {
                // if product is configurable - remove main product variant
                $data['variants'][0] = [];

                foreach ($children as $k => $child) {
                    $data['variants'][$k] = [
                        'id'                => $child->getId(),
                        'inventoryQuantity' => $this->stockItemRepository->get($child->getId())->getQty(),
                        'weight'            => $child->getWeight(),
                        'requiresShipping'  => true,
                        'sku'               => $child->getSku(),
                        'price'             => $this->helper->convertPrice($child->getPrice()),
                        'compareAtPrice'    => $this->helper->convertPrice($child->getSpecialPrice()),
                        'visibility'        => $child->getVisibility() != \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE,
                        'taxable'           => (bool)$child->getTaxClassId(),
                    ];

                    $imageData = $imagesData['children'][$child->getId()] ?? [];

                    // add images to each variant
                    if ($imageData && is_array($imageData)) {
                        $data['variants'][$k]['images'] = $imageData;
                    }

                    // if is "update" action - don't update the title
                    if ($isNew) {
                        $data['variants'][$k]['title'] = [
                            'en' => $child->getName(),
                        ];
                    }

                    // add available options for current variant
                    foreach ($data['variantOptions'] as $n => $attributeCode) {
                        $data['variants'][$k]['options'][] = [
                            'id'       => $attributeCode,
                            'name'     => [
                                'en' => $child->getResource()->getAttribute($attributeCode)->getFrontend()->getLabel($child),
                            ],
                            'position' => $n,
                            'value'    => [
                                'en' => $child->getAttributeText($attributeCode),
                            ],
                        ];
                    }
                }
            }
        }

        return $data;
    }
}
