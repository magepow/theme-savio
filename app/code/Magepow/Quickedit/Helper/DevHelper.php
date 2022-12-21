<?php

/**
 * @Author: nguyen
 * @Date:   2020-04-25 10:43:47
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-05-18 15:05:48
 */

namespace Magepow\Quickedit\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Developer\Helper\Data as Developer;

class DevHelper extends \Magepow\Quickedit\Helper\Data
{
    /**
     * XPath of configuration of the debug block names
     */
    const XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS = 'dev/debug/template_hints_blocks';

    /**
     * Quickedit allow ips config path
     */
    const XML_PATH_MAGEPOW_QUICKEDIT_ALLOW_IPS = 'magepow_quickedit/general/restrict_allow_ips';

    /**
     * XPath of configuration of the debug hints
     *
     * Allowed values:
     *     dev/debug/template_hints_storefront
     *     dev/debug/template_hints_admin
     *
     * @var string
     */
    protected $debugHintsPath   = 'dev/debug/template_hints_storefront';
    protected $quickeditEnabled = 'magepow_quickedit/general/enabled';
    protected $quickeditUseConfigDevDebug = 'magepow_quickedit/general/use_config_dev_debug';

    /**
     * XPath of configuration of the debug hints show with parameter
     *
     *     dev/debug/template_hints_storefront_show_with_parameter
     *
     * @var string
     */
    private $debugHintsWithParam = 'dev/debug/template_hints_storefront_show_with_parameter';

    private $quickeditWithParam  = 'magepow_quickedit/general/show_with_parameter';

    /**
     * XPath of configuration of the debug hints URL parameter
     *
     *     dev/debug/template_hints_parameter_value
     *
     * @var string
     */
    private $debugHintsParameter    = 'dev/debug/template_hints_parameter_value';

    private $quickeditParameter     = 'magepow_quickedit/general/parameter_value';

    /**
     * @var bool
     */
    private $_showHints;

    /**
     * @var bool
     */
    private $_showQuickedit;

    /**
     * @var bool
     */
    private $_enabledQuickedit;

    private $_store;
    /**
     * @var UrlInterface
     */
    protected $backendUrl;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        UrlInterface $backendUrl,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->backendUrl   = $backendUrl;
        $this->storeManager = $storeManager;
        $this->_store       = $this->storeManager->getStore();
        $this->_enabledQuickedit = $this->isQuickeditEnabled($this->_store->getCode());
    }

    public function getStore()
    {
        return $this->_store;
    }

    public function getRequest()
    {
        return $this->_getRequest();
    }

    public function getAdminUrl($adminPath, $routeParams=[], $storeCode = 'default' ) 
    {
        $routeParams[] = [ '_nosid' => true, '_query' => ['___store' => $storeCode]];
        return $this->backendUrl->getUrl($adminPath, $routeParams);
    }

    /**
     * Check if Quickedit is enabled
     *
     * @param string|null $storeId
     * @return string
     */
    public function isQuickeditEnabled($storeCode = 'default')
    {
        return $this->scopeConfig->getValue($this->quickeditEnabled, ScopeInterface::SCOPE_STORE, $storeCode);
    }

    /**
     * Check if the client remote address is allowed developer ip
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isAllowedIp($pathConfig, $storeId = null)
    {
        $allow = true;

        $allowedIps = $this->scopeConfig->getValue(
            $pathConfig,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $remoteAddr = $this->_remoteAddress->getRemoteAddress();
        if (!empty($allowedIps) && !empty($remoteAddr)) {
            $allowedIps = preg_split('#\s*,\s*#', $allowedIps, -1, PREG_SPLIT_NO_EMPTY);
            if (array_search($remoteAddr, $allowedIps) === false
                && array_search($this->_httpHeader->getHttpHost(), $allowedIps) === false
            ) {
                $allow = false;
            }
        }

        return $allow;
    }

    /**
     * Check if the client remote address is allowed developer ip
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isDevAllowed($storeId = null)
    {
        return $this->isAllowedIp(Developer::XML_PATH_DEV_ALLOW_IPS, $storeId);
    }

    /**
     * Check if the client remote address is allowed developer ip
     *
     * @param string|null $storeId
     * @return bool
     */
    public function isQuickEditAllowed($storeId = null)
    {
        return $this->isAllowedIp(Developer::XML_PATH_DEV_ALLOW_IPS, $storeId);
    }

    public function showQuickedit()
    {
        if (!$this->_enabledQuickedit) return;
        $storeCode  = $this->_store->getCode();
        if ($this->_showQuickedit != null) return $this->_showQuickedit;
        $showQuickedit = false;
        if ( $this->isQuickeditAllowed($storeCode)) {
            $quickeditWithParam = $this->scopeConfig->getValue(
                $this->quickeditWithParam,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            $quickeditParameter = $this->scopeConfig->getValue(
                $this->quickeditParameter,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            $quickeditParameterInUrl = $this->getRequest()->getParam('quickedit');

            if (!$quickeditWithParam) {
                $showQuickedit = true;
            }
            if ($quickeditWithParam && $quickeditParameter == $quickeditParameterInUrl) {
                $showQuickedit = true;
            }
        }
        if (!$showQuickedit){
            $quickeditUseConfigDevDebug = $this->scopeConfig->getValue(
                $this->quickeditUseConfigDevDebug,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            if ($quickeditUseConfigDevDebug) $showQuickedit = $this->showHints($storeCode);
        }
        $this->_showQuickedit = $showQuickedit;

        return $this->_showQuickedit;
    }

    public function showHints($storeCode='default')
    {
        if ($this->_showHints != null) return $this->_showHints;
        $showHints = false;
        if ($this->scopeConfig->getValue($this->debugHintsPath, ScopeInterface::SCOPE_STORE, $storeCode) 
            && $this->isDevAllowed($storeCode)) {
            $debugHintsWithParam = $this->scopeConfig->getValue(
                $this->debugHintsWithParam,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            $debugHintsParameter = $this->scopeConfig->getValue(
                $this->debugHintsParameter,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            $debugHintsParameterInUrl = $this->getRequest()->getParam('templatehints');

            if (!$debugHintsWithParam) {
                $showHints = true;
            }
            if ($debugHintsWithParam && $debugHintsParameter == $debugHintsParameterInUrl) {
                $showHints = true;
            }
        }
        $this->_showHints = $showHints;

        return $this->_showHints;
    }

    public function renderHints($blockHtml, $infoHints)
    {
        $hintsHtml = '';
        $color = [
            'blue',
            'red',
            'violet',
            'green'
        ];
        $i = 0;
        foreach ($infoHints as $key => $info) {
            $title = isset($info['title']) ? $info['title'] : '';
            $url   = isset($info['url']) ? $info['url']   : '#';
                $hintsHtml .= <<<HTML
<a class="debugging-hint-{$color[$i]}" style="position: absolute; padding: 2px 5px; font: normal 11px Arial; background: {$color[$i]}; color: white; white-space: nowrap;" onmouseover="this.style.zIndex = 999999999;" onmouseout="this.style.zIndex = 'auto';" href="{$url}" title="{$title}">{$title}</a>
HTML;
            $i++;
            if( $i%4 == 0 ) $i = 0;
        }
        $hintsHtml .=<<<HTML
        <style type="text/css">
            .debugging-hint-blue{
                left: 0;
                top: 0;
            }
            .debugging-hint-red{
                right: 0;
                top: 0;
            }
            .debugging-hint-violet{
                right: 0;
                bottom: 0;
            }
            .debugging-hint-green{
                left: 0;
                bottom: 0;
            }
        </style>
HTML;
        // @codingStandardsIgnoreStart
        return <<<HTML
<div class="debugging-hints-advanced" style="position: relative; z-index: 99; border: 1px dotted blue; margin: 6px 2px; padding: 18px 2px 2px 2px;">
{$hintsHtml}
{$blockHtml}
</div>
HTML;
        // @codingStandardsIgnoreEnd
    }
}
