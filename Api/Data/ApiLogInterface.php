<?php
/**
 * @package   Divante\Walkthechat
 *
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2019 Divante Sp. z o.o.
 *
 * @license   See LICENSE.txt for license details.
 */

namespace Divante\Walkthechat\Api\Data;

/**
 * Interface ApiLogInterface
 *
 * @package Divante\Walkthechat\Api\Data
 */
interface ApiLogInterface
{
    /**@#+
     *
     * Table fields
     */
    const ENTITY_ID_FIELD           = 'entity_id';
    const REQUEST_PATH_FIELD        = 'request_path';
    const REQUEST_METHOD_FIELD      = 'request_method';
    const REQUEST_PARAMS_FIELD      = 'request_params';
    const RESPONSE_CODE_FIELD       = 'response_code';
    const RESPONSE_DATA_FIELD       = 'response_data';
    const IS_SUCCESS_RESPONSE_FIELD = 'is_success_response';
    const CREATED_AT_FIELD          = 'created_at';
    /**@#- */

    /**
     * Return entity_id
     *
     * @return int
     */
    public function getId();

    /**
     * Set entity_id
     *
     * @param int $id
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setId($id);

    /**
     * Return request_path
     *
     * @return string
     */
    public function getRequestPath();

    /**
     * Set request_path
     *
     * @param string $path
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setRequestPath($path);

    /**
     * Return request_method
     *
     * @return string
     */
    public function getRequestMethod();

    /**
     * Set request_method
     *
     * @param string $method
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setRequestMethod($method);

    /**
     * Return request_params
     *
     * @return array
     */
    public function getRequestParams();

    /**
     * Set request_params
     *
     * @param array $params
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setRequestParams(array $params);

    /**
     * Return response_code
     *
     * @return int
     */
    public function getResponseCode();

    /**
     * Set response_code
     *
     * @param int $code
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setResponseCode($code);

    /**
     * Return response_data
     *
     * @return array
     */
    public function getResponseData();

    /**
     * Set response_data
     *
     * @param array $data
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setResponseData(array $data);

    /**
     * Return is_success_response
     *
     * @return bool
     */
    public function getIsSuccessResponse();

    /**
     * Set is_success_response
     *
     * @param bool $isSuccess
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setIsSuccessResponse($isSuccess);

    /**
     * Return created_at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $timestamp
     *
     * @return \Divante\Walkthechat\Api\Data\ApiLogInterface
     */
    public function setCreatedAt($timestamp);
}
