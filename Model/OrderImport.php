<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 * @license   See LICENSE.txt for license details.
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
     * @var \Divante\Walkthechat\Helper\Data
     */
    protected $helper;

    /**
     * OrderImport constructor.
     *
     * @param \Divante\Walkthechat\Model\Import\RequestValidator $requestValidator
     * @param \Divante\Walkthechat\Model\OrderService            $orderService
     * @param \Psr\Log\LoggerInterface                           $logger
     * @param \Divante\Walkthechat\Helper\Data                   $helper
     */
    public function __construct(
        \Divante\Walkthechat\Model\Import\RequestValidator $requestValidator,
        \Divante\Walkthechat\Model\OrderService $orderService,
        \Psr\Log\LoggerInterface $logger,
        \Divante\Walkthechat\Helper\Data $helper
    ) {
        $this->requestValidator = $requestValidator;
        $this->orderService     = $orderService;
        $this->logger           = $logger;
        $this->helper           = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function import(
        $id,
        $projectId,
        $financialStatus,
        $itemsToFulfill,
        $items,
        $deliveryAddress,
        $shippingRate,
        $tax,
        $total,
        $coupon = []
    ) {
        try {
            $this->helper->validateProjectId($projectId);

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
            $errorMessage = $exception->getMessage();
        } catch (\Divante\Walkthechat\Exception\NotSynchronizedProductException $exception) {
            $errorMessage = $exception->getMessage();
        } catch (\Divante\Walkthechat\Exception\InvalidMagentoInstanceException $exception) {
            $errorMessage = $exception->getMessage();
        } catch (\Exception $exception) {
            $this->logger->error('Error during the WalkTheChat order import | '.$exception->getMessage());

            $errorMessage = $exception->getMessage();
        }

        return json_encode(
            [
                'error'    => false,
                'message'  => $errorMessage,
                'order_id' => null,
            ]
        );
    }
}
