<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Model;

/**
 * Class OrderImport
 *
 * @package Divante\Walkthechat\Model
 */
class OrderImport implements \Divante\Walkthechat\Api\OrderImportInterface
{
    /**
     * @var \Divante\Walkthechat\Model\Import\RequestValidator
     */
    protected $requestValidator;

    /**
     * @var \Divante\Walkthechat\Model\OrderService
     */
    protected $orderService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * OrderImport constructor.
     *
     * @param \Divante\Walkthechat\Model\Import\RequestValidator $requestValidator
     * @param \Divante\Walkthechat\Model\OrderService            $orderService
     * @param \Psr\Log\LoggerInterface                           $logger
     */
    public function __construct(
        \Divante\Walkthechat\Model\Import\RequestValidator $requestValidator,
        \Divante\Walkthechat\Model\OrderService $orderService,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->requestValidator = $requestValidator;
        $this->orderService     = $orderService;
        $this->logger           = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function import($id, $financialStatus, $itemsToFulfill, $items, $deliveryAddress, $shippingRate, $tax, $total, $coupon = [])
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();

        $filesystem = $om->get('Magento\Framework\Filesystem');
        $media = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

        $contents = var_export([$id, $financialStatus, $itemsToFulfill, $items, $deliveryAddress, $shippingRate, $tax, $total, $coupon], true);
        $media->writeFile("wtc_request.txt", $contents);

        try {
            $data = $this->requestValidator->validate(
                $id,
                $financialStatus,
                $itemsToFulfill,
                $items,
                $deliveryAddress,
                $shippingRate,
                $tax,
                $total,
                $coupon
            );

            $order = $this->orderService->processImport($data);

            return json_encode([
                'error'    => false,
                'order_id' => $order->getEntityId(),
            ]);
        } catch (\Magento\Framework\Exception\ValidatorException $exception) {
            return json_encode(
                [
                    'error'    => false,
                    'message'  => $exception->getMessage(),
                    'order_id' => null,
                ]
            );
        } catch (\Exception $e) {
            $this->logger->error('Error during the WalkTheChat order import | ' . $e->getMessage());

            return json_encode(
                [
                    'error'    => true,
                    'message'  => 'An error has been occurred. Please contact administrator for more information.',
                    'order_id' => null,
                ]
            );
        }
    }
}
