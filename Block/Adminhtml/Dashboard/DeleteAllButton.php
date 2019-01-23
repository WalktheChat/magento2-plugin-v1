<?php
/**
 * @package   Divante\Walkthechat
 * @author    Oleksandr Yeremenko <oyeremenko@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\Walkthechat\Block\Adminhtml\Dashboard;

/**
 * Class DeleteAllButton
 *
 * @package Divante\Walkthechat\Block\Adminhtml\Log\Details
 */
class DeleteAllButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Delete All Products from Walkthechat'),
            'on_click'   => sprintf(
                'deleteConfirm("This will delete ALL products from your WalktheChat store.<br><br>Are you sure you want to proceed?", "%s")',
                $this->getDeleteAllUrl()
            ),
            'class'      => 'delete',
            'sort_order' => 500,
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getDeleteAllUrl()
    {
        return $this->getUrl('*/product/deleteAll');
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array  $params
     *
     * @return  string
     */
    protected function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
