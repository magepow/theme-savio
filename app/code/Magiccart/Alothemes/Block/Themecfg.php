<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.css
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2016-02-28 10:10:00
 * @@Modify Date: 2020-08-08 18:21:47
 * @@Function:
 */
namespace Magiccart\Alothemes\Block;

use Magento\Framework\App\Filesystem\DirectoryList;

class Themecfg extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{
    const DEFAULT_CACHE_TAG = 'MAGICCART_ALOTHEMES';

    public $_helper;
    public $_themeCfg;
    public $_rtl;
    public $assetRepository;
    public $filesystem;
    public $cssFile = '_cache/merged/stores/%s/alothemes_custom.css';
    public $storeManager;
    protected $directorySaticView;
    protected $storeId;

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var State
     */
    private $appState;
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magiccart\Alothemes\Helper\Data $_helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->request         = $context->getRequest();
        $this->appState        = $context->getAppState();
        $this->filesystem      = $context->getFilesystem();
        $this->storeManager    = $context->getStoreManager();
        $this->assetRepository = $context->getAssetRepository();
        $this->_helper         = $_helper;
        $this->_themeCfg       = $this->_helper->getThemeCfg();
        $this->pageConfig      = $context->getPageConfig();
        // $mergeCss              = $this->_helper->getConfig('dev/css/merge_css_files');
        // if(!$mergeCss) $mergeCss = $this->_helper->getConfig('alodesign/general/developer');
        $mergeCss              = $this->_helper->getConfigModule('css/merge_css_files');
        // if( $mergeCss || $this->_appState->getMode() == State::MODE_PRODUCTION ){
        if($mergeCss){
            $this->storeId = $this->storeManager->getStore()->getId();
            $this->cssFile = sprintf($this->cssFile, $this->storeId);
            $this->createAsset();
        } else {

            $this->cssFile = '';
        }

        $this->addBodyClass();
    }

    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 86400;
    }

    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $keyInfo[]   =  self::DEFAULT_CACHE_TAG;
        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::DEFAULT_CACHE_TAG, self::DEFAULT_CACHE_TAG];
    }

    public function createAsset()
    {
        $this->directorySaticView = $this->filesystem->getDirectoryWrite(DirectoryList::STATIC_VIEW);
        $cssFilePath = $this->directorySaticView->getAbsolutePath($this->cssFile);
        if(!$this->directorySaticView->isExist($cssFilePath)){
            try {
                $css = $this->generalCss();
                $this->directorySaticView->writeFile($this->cssFile, $css);
            } catch (FileSystemException $e) {
                $this->cssFile = '';
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
          
        }

    }

    public function generalCss()
    {
        return $this->_helper->generalCss();
    }

    public function getExtraCssUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC) . $this->cssFile;
    }

    public function getLivereload()
    {
        return $this->request->getParam('livereload', false) ? '//' . $this->request->getHttpHost() . ':35729' : false;
    }

    /**
     * @return string
     */
    public function getHostUrl()
    {
        return  '//' . $this->request->getHttpHost() . ':35729';
        // $scheme = $this->request->isSecure() ? 'https' : 'http';
        // return $scheme . '://' . $this->request->getHttpHost() . ':35729';
    }

    public function addBodyClass()
    {
        $customLayout = [
            '1column-full-width'  => 'page-layout-1column',
            '2columns-full-width' => 'page-layout-2columns-left'
        ];
        $pageLayout = $this->pageConfig->getPageLayout();
        foreach ($customLayout as $layout => $class) {
            if($layout != $pageLayout) continue;
            $this->pageConfig->addBodyClass($class);
        }

        $this->_rtl = ($this->_helper->getConfigModule('rtl/enabled')) ? 'rtl' : '';
        if($this->_rtl) $this->pageConfig->addBodyClass($this->_rtl);
        $widescreen = ($this->_helper->getConfigModule('widescreen/enabled')) ? 'widescreen' : '';
        if($widescreen) $this->pageConfig->addBodyClass($widescreen);
    }

}
