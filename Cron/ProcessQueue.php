<?php
namespace Divante\Walkthechat\Cron;

use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Framework\App\Config\MutableScopeConfigInterface as ScopeConfig;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Store\Model\Store;

/**
 * Walkthechat Process Queue Cronjob
 *
 * @package  Divante\Walkthechat
 * @author   Divante Tech Team <tech@divante.pl>
 */
class ProcessQueue
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory
     */
    protected $queueCollectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Divante\Walkthechat\Service\Products
     */
    protected $productsService;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Divante\Walkthechat\Model\QueueRepository
     */
    protected $queueRepository;

    /**
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * ProcessQueue constructor.
     * @param \Magento\Framework\App\State $state
     * @param \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $queueCollectionFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Divante\Walkthechat\Service\Products $productsService
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Divante\Walkthechat\Model\QueueRepository $queueRepository
     * @param \Divante\Walkthechat\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \Divante\Walkthechat\Model\ResourceModel\Queue\CollectionFactory $queueCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Divante\Walkthechat\Service\Products $productsService,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Divante\Walkthechat\Model\QueueRepository $queueRepository,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->state = $state;
        $this->queueCollectionFactory = $queueCollectionFactory;
        $this->date = $date;
        $this->productsService = $productsService;
        $this->productRepository = $productRepository;
        $this->queueRepository = $queueRepository;
        $this->helper = $helper;
    }

    public function execute()
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        /** @var \Divante\Walkthechat\Model\ResourceModel\Queue\Collection $collection */
        $collection = $this->queueCollectionFactory->create();
        $collection->setPageSize(10)->setCurPage(1)->addOnlyForSyncFilter()->load();

        foreach ($collection as $row) {
            switch ($row->getAction()) {
                case 'delete':
                    $this->productsService->delete(['id' => $row->getWalkthechatId()]);
                    break;
                case 'add':
                    $product = $this->productRepository->getById($row->getProductId());
                    $data = $this->helper->prepareProductData($product);
                    $walkthechatId = $this->productsService->create($data);

                    $product->setWalkthechatId($walkthechatId);
                    $this->productRepository->save($product);
                    break;
                case 'update':
                    if ($row->getProductId()) {
                        $product = $this->productRepository->getById($row->getProductId());
                        $data = $this->helper->prepareProductData($product);
                        $this->productsService->update($data, $this->getWalkthechatId());
                    } elseif ($row->getOrderId()) {
                        /** TO-DO update order data **/
                    }
            }

            $row->setProcessedAt($this->date->gmtDate());
            $this->queueRepository->save($row);
        }
    }
}
