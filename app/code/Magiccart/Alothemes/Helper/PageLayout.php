<?php

namespace Magiccart\Alothemes\Helper;

class PageLayout extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\Page\Config $pageConfig
    )
    {
        $this->layout = $layout;
        $this->pageConfig = $pageConfig;
    }

    public function getPageLayout() {
        return $this->pageConfig->getPageLayout() ? $this->pageConfig->getPageLayout() : $this->layout->getUpdate()->getPageLayout();
    }
}