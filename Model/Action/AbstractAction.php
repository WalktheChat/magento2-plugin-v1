<?php
/**
 * @package   Divante\Walkthechat
 * @author    Alex Yeremenko <madonzy13@gmail.com>
 * @copyright 2019 WalktheChat
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Model\Action;

/**
 * Class AbstractAction
 *
 * @package Divante\Walkthechat\Model\Action
 */
abstract class AbstractAction
{
    /**
     * @var \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory
     */
    protected $imageSyncFactory;

    /**
     * @var \Divante\Walkthechat\Api\ImageSyncRepositoryInterface
     */
    protected $imageSyncRepository;

    /**
     * AbstractAction constructor.
     *
     * @param \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory $imageSyncFactory
     * @param \Divante\Walkthechat\Api\ImageSyncRepositoryInterface   $imageSyncRepository
     */
    public function __construct(
        \Divante\Walkthechat\Api\Data\ImageSyncInterfaceFactory $imageSyncFactory,
        \Divante\Walkthechat\Api\ImageSyncRepositoryInterface $imageSyncRepository
    ) {
        $this->imageSyncFactory    = $imageSyncFactory;
        $this->imageSyncRepository = $imageSyncRepository;
    }

    /**
     * Execute action and return bool value depends on if process was successful
     *
     * @param \Divante\Walkthechat\Api\Data\QueueInterface $queueItem
     *
     * @return bool
     */
    public abstract function execute(\Divante\Walkthechat\Api\Data\QueueInterface $queueItem);

    /**
     * Saves images into image sync table
     *
     * @param array $data
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function saveImagesToSyncTable(array $data)
    {
        foreach ($data as $item) {
            /** @var \Divante\Walkthechat\Model\ImageSync $model */
            $model = $this->imageSyncFactory->create();

            $model->setData($item);

            $this->imageSyncRepository->save($model);
        }
    }
}
