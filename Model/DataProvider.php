<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class DataProvider
 *
 * @package Divante\Walkthechat\Model
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Divante\Walkthechat\Api\ApiLogRepositoryInterface
     */
    protected $apiLogRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * {@inheritdoc}
     *
     * @param \Divante\Walkthechat\Api\ApiLogRepositoryInterface $apiLogRepository
     * @param \Magento\Framework\App\RequestInterface            $request
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        \Divante\Walkthechat\Api\ApiLogRepositoryInterface $apiLogRepository,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->apiLogRepository = $apiLogRepository;
        $this->request          = $request;

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $id     = $this->request->getParam($this->primaryFieldName);
        $entity = $this->apiLogRepository->getById($id);

        $this->_loadedData[$entity->getId()] = $entity->getData();

        return $this->_loadedData;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
}
