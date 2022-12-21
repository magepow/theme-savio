<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-08-26 15:31:37
 * @@Modify Date: 2019-05-30 23:51:51
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Widget;

use \Magiccart\Alothemes\Model\Design\Frontend\Responsive;
/**
 *  Widget Blog
 */
class Blog extends \Magefan\Blog\Block\Post\PostList\AbstractList implements \Magento\Widget\Block\BlockInterface
{
    // use Widget;

    /**
     * @var string
     */
    protected $_widgetKey = 'slide_posts';

    /**
     * @return $this
     */
    public function _construct()
    {
        $this->setPageSize((int) $this->getData('limit'));

        if($this->getData('slide')){
            $data['vertical-Swiping'] = $this->getData('vertical');
            $breakpoints = $this->getResponsiveBreakpoints();
            $responsive = '[';
            $num = count($breakpoints);
            foreach ($breakpoints as $size => $opt) {
                $item = (int)  $this->getData($opt);
                $num--;
                if(!$item) continue;
                $responsive .= '{"breakpoint": '.$size.', "settings": {"slidesToShow": '.$item.'}}';
                if($num) $responsive .= ', ';
            }
            $responsive .= ']';
            $data['slides-To-Show'] = $this->getData('visible');
            $data['autoplay-Speed'] = $this->getData('autoplay_speed');
            if(!empty($this->getData('adaptive_height'))) $data['adaptive-Height']= $this->getData('adaptive_height');
            $data['swipe-To-Slide'] = 'true';
            $data['responsive'] = $responsive;
            
            $this->addData($data);
        }

        return parent::_construct();
    }

    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_blog_slide_posts_widget'  ];
    }

    public function getPostedOn($_post, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($_post->getData('publish_time')));
    }

    public function getShorContent($_post)
    {
        
        if(method_exists($_post,'getShortFilteredContent')) return $_post->getShortFilteredContent();

        $content = $_post->getContent();
        $pageBraker = '<!-- pagebreak -->';
        
        $isMb = function_exists('mb_strpos');
        $p = $isMb ? strpos($content, $pageBraker) : mb_strpos($content, $pageBraker);

        if ($p) {
            $content = substr($content, 0, $p);
        }

        return $this->_filterProvider->getPageFilter()->filter($content);
    }


    public function getResponsiveBreakpoints()
    {
        return Responsive::getBreakpoints();
    }

    public function getSlideOptions()
    {
        return array('autoplay', 'arrows', 'autoplay-Speed', 'adaptive-Height', 'dots', 'infinite', 'padding', 'vertical', 'vertical-Swiping', 'responsive', 'rows', 'slides-To-Show', 'swipe-To-Slide');
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
