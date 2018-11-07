<?php
namespace Divante\Walkthechat\Service;

/**
 * Walkthechat Authorize Service
 *
 * @package  Divante\Walkthechat\Service
 * @author   Divante Tech Team <tech@divante.pl>
 */
class Authorize extends AbstractService
{
    /**
     * @param $code
     * @return string
     */
    public function authorize($code)
    {
        $resourceModel = $this->_objectManager->create(Resource\Authorize::class);

        $data = [
            'code' => $code,
            'appId' => '',
            'appSecret' => '',
        ];

        $result = $this->request($resourceModel, $data);

        return $result;
    }
}
