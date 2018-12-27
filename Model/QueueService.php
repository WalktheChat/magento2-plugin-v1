<?php

namespace Divante\Walkthechat\Model;

/**
 * @package   Divante\Walkthechat
 * @author    Divante Tech Team <tech@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */
class QueueService
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var QueueFactory
     */
    protected $queueFactory;

    /**
     * @var QueueRepository
     */
    protected $queueRepository;

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
     * @var \Divante\Walkthechat\Service\ProductsRepository
     */
    protected $queueProductRepository;

    /**
     * @var \Divante\Walkthechat\Service\OrdersRepository
     */
    protected $queueOrderRepository;

    /**
     * QueueService constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime     $date
     * @param \Divante\Walkthechat\Helper\Data                $helper
     * @param \Magento\Catalog\Model\ProductRepository        $productRepository
     * @param \Magento\Sales\Model\OrderRepository            $orderRepository
     * @param QueueFactory                                    $queueFactory
     * @param QueueRepository                                 $queueRepository
     * @param \Magento\Framework\Api\SearchCriteriaInterface  $searchCriteria
     * @param \Magento\Framework\Api\Search\FilterGroup       $filterGroup
     * @param \Magento\Framework\Api\FilterBuilder            $filterBuilder
     * @param \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository
     * @param \Divante\Walkthechat\Service\OrdersRepository   $queueOrderRepository
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Divante\Walkthechat\Helper\Data $helper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        QueueFactory $queueFactory,
        QueueRepository $queueRepository,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Divante\Walkthechat\Service\ProductsRepository $queueProductRepository,
        \Divante\Walkthechat\Service\OrdersRepository $queueOrderRepository
    ) {
        $this->date                   = $date;
        $this->helper                 = $helper;
        $this->productRepository      = $productRepository;
        $this->orderRepository        = $orderRepository;
        $this->queueFactory           = $queueFactory;
        $this->queueRepository        = $queueRepository;
        $this->searchCriteria         = $searchCriteria;
        $this->filterGroup            = $filterGroup;
        $this->filterBuilder          = $filterBuilder;
        $this->queueProductRepository = $queueProductRepository;
        $this->queueOrderRepository   = $queueOrderRepository;
    }

    /**
     * Add new row to queue
     *
     * @param array $data
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function create($data)
    {
        $model = $this->queueFactory->create();
        $model->setData($data);

        return $this->queueRepository->save($model);
    }

    /**
     * Get all not processed rows
     *
     * @return \Divante\Walkthechat\Api\Data\QueueInterface[]
     */
    public function getAllNotProcessed()
    {
        $this->filterGroup->setFilters([
            $this->filterBuilder
                ->setField('processed_at')
                ->setConditionType('null')
                ->create(),
        ]);

        $this->searchCriteria->setFilterGroups([$this->filterGroup]);
        $results = $this->queueRepository->getList($this->searchCriteria);

        return $results->getItems();
    }

    /**
     * Sync item with Walkthechat
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $item
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Zend_Http_Client_Exception
     */
    public function sync($item)
    {
        $error = false;

        switch ($item->getAction()) {
            case 'delete':
                $this->queueProductRepository->delete(['id' => $item->getWalkthechatId()]);
                break;
            case 'add':
                $product = $this->productRepository->getById($item->getProductId());
                $data    = $this->helper->prepareProductData($product);
                $id      = $this->queueProductRepository->create($data);

                if ($id) {
                    $product->setWalkthechatId($id);
                    $this->productRepository->save($product);
                } else {
                    $error = true;
                }
                break;
            case 'update':
                if ($item->getProductId()) {
                    $product = $this->productRepository->getById($item->getProductId());
                    $data    = $this->helper->prepareProductData($product, false);

                    $data['id'] = $item->getWalkthechatId();

                    $this->queueProductRepository->update($data);
                } elseif ($item->getOrderId()) {
                    $order = $this->orderRepository->get($item->getOrderId());
                    $data  = $this->helper->prepareOrderData($order);
                    $this->queueOrderRepository->update($data);
                }
        }

        if (!$error) {
            $item->setProcessedAt($this->date->gmtDate());
            $this->queueRepository->save($item);
        }
    }
}
