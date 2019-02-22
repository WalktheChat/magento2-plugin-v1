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
 * Class ApiLog
 *
 * @package Divante\Walkthechat\Model
 */
class ApiLog extends \Magento\Framework\Model\AbstractModel implements \Divante\Walkthechat\Api\Data\ApiLogInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Divante\Walkthechat\Model\ResourceModel\ApiLog::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestPath()
    {
        return $this->getData(self::REQUEST_PATH_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestPath($path)
    {
        return $this->setData(self::REQUEST_PATH_FIELD, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestMethod()
    {
        return $this->getData(self::REQUEST_METHOD_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestMethod($method)
    {
        return $this->setData(self::REQUEST_METHOD_FIELD, $method);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParams()
    {
        return json_decode($this->getData(self::REQUEST_PARAMS_FIELD), true);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestParams(array $params)
    {
        $params = json_encode($params, JSON_PRETTY_PRINT);

        return $this->setData(self::REQUEST_PARAMS_FIELD, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseCode()
    {
        return $this->getData(self::RESPONSE_CODE_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseCode($code)
    {
        return $this->setData(self::RESPONSE_CODE_FIELD, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseData()
    {
        return json_decode($this->getData(self::RESPONSE_DATA_FIELD), true);
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData(array $data)
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return $this->setData(self::RESPONSE_DATA_FIELD, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsSuccessResponse()
    {
        return (bool)$this->getData(self::IS_SUCCESS_RESPONSE_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSuccessResponse($isSuccess)
    {
        return $this->setData(self::IS_SUCCESS_RESPONSE_FIELD, $isSuccess);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($timestamp)
    {
        return $this->setData(self::CREATED_AT_FIELD, $timestamp);
    }
}
