<?php
namespace Divante\Walkthechat\Service\Resource\Products;

/**
 * Walkthechat Service Products Create Resource
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Create extends \Divante\Walkthechat\Service\Resource\AbstractResource
{
    /**
     * @var string
     */
    protected $type = 'POST';

    /**
     * @var string
     */
    protected $path = 'products';

    /**
     * @var array
     */
    protected $headers = [
        'x-access-token' => '',
        'Accept' => "application/json, appl-header 'Content-Type: application/json",
        'Content-Type' => "application/json"
    ];
}
