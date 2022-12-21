<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-05-31 15:01:42
 * @@Function:
 */

namespace Magiccart\Testimonial\Block\Widget;
// use Magento\Framework\App\Filesystem\DirectoryList;

class Testimonial extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
    * @var \Magento\Framework\Image\AdapterFactory
    */
    protected $_imageFactory;

    // protected $_filesystem;
    // protected $_directory;

    /**
    * @var \Magento\Backend\Model\UrlInterface
    */
    protected $backendUrl;

    /**
    * @var \Magiccart\Testimonial\Model\TestimonialFactory
    */
    protected $testimonialFactory;

    protected $_testimonials;

    /**
     * @var \Magiccart\Testimonial\Model\Config\Source\GridSlider
     */    
    protected $gridSlider;

    /**
     * @var \Magiccart\Testimonial\Helper\Data
     */ 
    public $helper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        // \Magento\Framework\Filesystem $filesystem,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
        \Magiccart\Testimonial\Model\Config\Source\GridSlider $gridSlider,
        \Magiccart\Testimonial\Helper\Data $helper,

        array $data = []
    ) {

        $this->_imageFactory      = $imageFactory;
        // $this->_filesystem        = $filesystem;
        // $this->_directory         = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->backendUrl         = $backendUrl;
        $this->testimonialFactory = $testimonialFactory;
        $this->gridSlider         = $gridSlider;
        $this->helper             = $helper;

        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $data = $this->helper->getConfigModule('general');
        if($data['slide']){
            $data['vertical-swiping'] = $data['vertical'];
            $breakpoints = $this->getResponsiveBreakpoints();
            $responsive = '[';
            $num = count($breakpoints);
            foreach ($breakpoints as $size => $opt) {
                $item = (int) $data[$opt];
                $responsive .= '{"breakpoint": '.$size.', "settings": {"slidesToShow": '.$item.'}}';
                $num--;
                if($num) $responsive .= ', ';
            }
            $responsive .= ']';
            $data['center-mode']     = $data['center_mode'];
            $data['slides-to-show']  = $data['visible'];
            $data['autoplay-speed']  = $data['autoplay_speed'];
            $data['adaptive-height'] = $data['adaptive_height'];
            $data['swipe-to-slide']  = 'true';
            $data['responsive']      = $responsive;
            // if(!isset($data['rows'])  || $data['rows'] == 1 ) $data['rows'] = 0;
        }

        $this->addData($data);

        parent::_construct();

    }

    public function getAdminUrl($adminPath, $routeParams=[], $storeCode = 'default' ) 
    {
        $routeParams[] = [ '_nosid' => true, '_query' => ['___store' => $storeCode]];
        return $this->backendUrl->getUrl($adminPath, $routeParams);
    }

    public function getQuickedit()
    {
        // $testimonials = $this->getTestimonials();
        // if($testimonials){
            $routeParams = [
                // 'testimonial_id' => $id
            ];
            $class      = 'Testimonial'; //basename(__FILE__, ".php");
            $adminPath  = 'testimonial/index/index';
            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
            $moduleName = $this->getModuleName();
            $moduleName = str_replace('_', ' > ', $moduleName);
            $quickedit  = [
                [
                    'title' => __('%1 > %2 :', $moduleName, $class),
                    'url'   => $editUrl
                ],
                [
                    'title' => __('Edit'),
                    'url'   => $editUrl
                ]
            ];
        // }

        return $quickedit;      
    }

    public function getTestimonials()
    {
        if(!$this->_testimonials){
            $store = $this->_storeManager->getStore()->getStoreId();
            $testimonials = $this->testimonialFactory->create()->getCollection()
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
            $testimonials->getSelect()->order(array('order asc', 'testimonial_id desc'));
            $this->_testimonials = $testimonials;
        }

        return $this->_testimonials;
    }

    public function getImage($object)
    {
        // $width  =200;
        // $height = 200;
        // $directory = $width . 'x' . $height;
        // $image = $object->getImage();
        // $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath().$image;
        // $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($directory).$image;
        // $imageResize = $this->_imageFactory->create();
        // $imageResize->open($absPath);
        // $imageResize->constrainOnly(TRUE);
        // $imageResize->keepTransparency(TRUE);
        // $imageResize->keepFrame(FALSE);
        // $imageResize->keepAspectRatio(true);
        // $imageResize->resize($width, $height);
        // $dest = $imageResized ;
        // $imageResize->save($dest);
        // $resizedURL= $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$directory.$image;
        
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $object->getImage();
        return $resizedURL;
    }

    public function getResponsiveBreakpoints()
    {
        return $this->gridSlider->getBreakpoints();
    }

    public function getSlideOptions()
    {
        return $this->gridSlider->getSlideOptions();
    }

    public function getFrontendCfg()
    { 
        if($this->getSlide()) return $this->getSlideOptions();

        $this->addData(array('responsive' =>json_encode($this->getGridOptions())));
        return array('padding', 'responsive');

    }

    public function getGridOptions()
    {
        $options = array();
        $breakpoints = $this->getResponsiveBreakpoints(); ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size-1 => $this->getData($screen));
        }
        return $options;
    }

}
