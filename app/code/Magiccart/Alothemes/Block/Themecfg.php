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
class Themecfg extends \Magento\Framework\View\Element\Template
{
    public $_themeCfg;
    public $_time;
    public $_rtl;
    public $_scopeConfig;
    public $assetRepository;
    public $filesystem;
    public $cssFile = '_cache/merged/stores/%s/alothemes_custom.css';
    public $storeManager;
    protected $_dir;
    protected $storeId;
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
        \Magento\Framework\Stdlib\DateTime\DateTime $time,
        \Magiccart\Alothemes\Helper\Data $_helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_time        = $time;
        $this->_themeCfg    = $_helper->getThemeCfg();
        $this->_scopeConfig = $context->getScopeConfig();
        $this->filesystem   = $context->getFilesystem();
        $this->appState     = $context->getAppState();
        $this->request      = $context->getRequest();
        $this->assetRepository = $context->getAssetRepository();

        $this->_rtl = (isset($this->_themeCfg['rtl']['enabled']) && $this->_themeCfg['rtl']['enabled']) ? 'rtl' : '';
        if($this->_rtl) $context->getPageConfig()->addBodyClass($this->_rtl);
        $widescreen = (isset($this->_themeCfg['widescreen']['enabled']) && $this->_themeCfg['widescreen']['enabled']) ? 'widescreen' : '';
        if($widescreen) $context->getPageConfig()->addBodyClass($widescreen);
        // $loading_body = (isset($this->_themeCfg['preload']['loading_body']) && $this->_themeCfg['preload']['loading_body']) ? 'loading_body' : '';
        // if($loading_body) $context->getPageConfig()->addBodyClass($loading_body);
        // $loading_img = (isset($this->_themeCfg['preload']['loading_img']) && $this->_themeCfg['preload']['loading_img']) ? 'loading_img' : '';
        // if($loading_img) $context->getPageConfig()->addBodyClass($loading_img);
        $mergeCss = $this->_scopeConfig->getValue( 'dev/css/merge_css_files', \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        // if( $mergeCss || $this->_appState->getMode() == State::MODE_PRODUCTION ){
        if($mergeCss){
            $this->storeManager = $context->getStoreManager();
            $this->storeId      = $this->storeManager->getStore()->getId();
            $this->cssFile      = sprintf($this->cssFile, $this->storeId);

            $this->createAsset();
        } else {
            $this->cssFile = '';
        }


    }

    public function createAsset()
    {
        $this->_dir = $this->filesystem->getDirectoryWrite(DirectoryList::STATIC_VIEW);
        $cssFilePath = $this->_dir->getAbsolutePath($this->cssFile);
        if(!$this->_dir->isExist($cssFilePath)){
            try {
                $css = $this->generalCss();
                $this->_dir->writeFile($this->cssFile, $css);
            } catch (FileSystemException $e) {
                $this->cssFile = '';
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
          
        }

    }

    public function generalCss()
    {
        $cfg = $this->_themeCfg;
        $css ='';
        $fontCfg   = $cfg['font'];
        $font      = $fontCfg['google'] ? $fontCfg['google'] : $fontCfg['custom'];
        /* CssGenerator */
        $css  .= 'body{font-size: ' . $fontCfg['size'] . ';';
        if($font) $css  .= "font-family: '" . $font . "', sans-serif";
        $css  .= '}';

        $design = $this->_scopeConfig->getValue( 'alodesign', \Magento\Store\Model\ScopeInterface::SCOPE_STORE );

        if (is_array($design) || is_object($design)){
            foreach ($design as $group => $options) 
            {
                foreach ($options as $property => $values) {
                    $tmp = json_decode($values, true);
                    if(json_last_error() == JSON_ERROR_NONE){
                        $values = $tmp;
                    } else {
                        $values = @unserialize($values);
                    }
                    if(is_array($values) || is_object($values)){
                        foreach ($values as $value) {
                            if(!$value) continue;
                            $css .= htmlspecialchars_decode($value['selector']) .'{';
                                $css .= $value['color']        ? 'color:' .$value['color']. ';'                    : '';
                                $css .= $value['background']   ? ' background-color:' .$value['background']. ';'   : '';
                                $css .= $value['border']       ? ' border-color:' .$value['border']. ';'           : '';
                            $css .= '}';
                        }               
                    }

                }
            }
        }

        if(isset($design['extra_css']['color'])) $css .= $design['extra_css']['color'];

        return $css;
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

}
