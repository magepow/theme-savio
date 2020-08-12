<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magepow.com/) 
 * @license     http://www.magepow.com/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-02-14 20:26:27
 * @@Modify Date: 2020-06-27 16:14:15
 * @@Function:
 */

namespace Magiccart\Alothemes\Helper;

use Magiccart\Alothemes\Model\Design\Frontend\Responsive;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $configModule;

    protected $_labels = null;
    protected $_timer  = null;
    protected $_themeCfg = array();

    /**
     * @var string
     */
    protected $pageConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\View\Page\Config $pageConfig
    )
    {
        $this->pageConfig   = $pageConfig;
        parent::__construct($context);
        $this->configModule = $this->getConfig('alothemes');
    }
    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getThemeCfg($cfg='')
    {
        if(!$this->_themeCfg) $this->_themeCfg = $this->configModule;
        if(!$cfg) return $this->_themeCfg;
        elseif(isset($this->_themeCfg[$cfg])) return $this->_themeCfg[$cfg];
    }

    public function getConfigModule($cfg='', $value=null)
    {
        $values = $this->configModule;
        if( !$cfg ) return $values;
        $config  = explode('/', $cfg);
        $end     = count($config) - 1;
        foreach ($config as $key => $vl) {
            if( isset($values[$vl]) ){
                if( $key == $end ) {
                    $value = $values[$vl];
                }else {
                    $values = $values[$vl];
                }
            } 

        }
        return $value;
    }

    public function getTimer($_product)
    {
        if($this->_timer==null) $this->_timer = $this->getThemeCfg('timer');
        if(!$this->_timer['enabled']) return;

        if($_product->getTypeId() == 'configurable'){
                $products = $_product->getTypeInstance()->getUsedProducts($_product, null);
                $date = 0;
                foreach ($products as $child) {
                    if ($child->isSaleable()) {
                        $toDate     = $child->getSpecialToDate();
                        if(!$toDate) continue;
                        if ($date) {
                            if (strtotime($date) < strtotime($toDate)) {
                                $_product = $child;
                                $date = $toDate;
                            }
                        } else {
                            $_product = $child;
                            $date = $toDate;
                        }
                    }
                }        
        }

        $toDate = $_product->getSpecialToDate();
        if(!$toDate) return;
        if($_product->getPrice() < $_product->getSpecialPrice()) return;
        if($_product->getSpecialPrice() == 0 || $_product->getSpecialPrice() == "") return;
        $timer = strtotime($toDate) - strtotime("now");
        return ($timer > 0) ? '<div class="alo-count-down"><div class="countdown" data-timer="' .$timer. '"></div></div>' : '';

        $now = new \DateTime();
        $ends = new \DateTime($toDate);
        $left = $now->diff($ends);
        return '<div class="alo-count-down"><span class="countdown" data-d="' .$left->format('%a'). '" data-h="' .$left->format('%h'). '" data-i="' .$left->format('%h'). '" data-s="' .$left->format('%s'). '"></span></div>';
    }

    public function getLabels($_product)
    {
        if($this->_labels==null) $this->_labels = $this->getThemeCfg('labels');
        $html  = '';
        $newText = isset($this->_labels['newText']) ? $this->_labels['newText'] : ''; // get in Cfg;
        if($newText && $this->isNew($_product)) $html .= '<span class="sticker top-left"><span class="labelnew">' . __($newText) . '</span></span>';
        $percent = isset($this->_labels['salePercent']) ? $this->_labels['salePercent'] : false; // get in Cfg;
        $saleLabel = '';
        if( $_product->getTypeId() == 'configurable' ){
            // $special_price = $_product->getPriceInfo()->getPrice('special_price')->getAmount()->getBaseAmount();
            $final_price   = $_product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount();
            $regular_price = $_product->getPriceInfo()->getPrice('regular_price')->getAmount()->getBaseAmount();
            if($final_price < $regular_price){
                if($percent){
                     $saleLabel = (int)$regular_price ? floor(($final_price/$regular_price)*100 - 100).'%' : $this->_labels['zeroText']; 
                } else {
                    $saleLabel = isset($this->_labels['saleText']) ? $this->_labels['saleText'] : '';
                }
            }
            if(!$saleLabel){
                $products = $_product->getTypeInstance()->getUsedProducts($_product, null);
                $discount = 0;
                foreach ($products as $child) {
                    if ($child->isSaleable()) {
                        if($this->isOnSale($child)) {
                            if($percent){
                                $price = $child->getPrice();
                                $finalPrice = $child->getFinalPrice();
                                $saleOff = floor( ($finalPrice/$price)*100 - 100 );
                                if((int)$price && ($saleOff < $discount)){
                                    $discount   = $saleOff;
                                    $saleLabel = $saleOff .'%';          
                                }      
                            }else {
                                $saleLabel = isset($this->_labels['saleText']) ? $this->_labels['saleText'] : '';
                                break;
                            }
                        }
                    }
                }
            }
        } else if($this->isOnSale($_product)) {
            if($percent){
                $price = $_product->getPrice();
                $finalPrice = $_product->getFinalPrice();
                $saleLabel = (int)$price ? floor(($finalPrice/$price)*100 - 100).'%' : $this->_labels['zeroText'];                
            }else {
                $saleLabel = isset($this->_labels['saleText']) ? $this->_labels['saleText'] : '';
            }
        }

        if($saleLabel) $html .= '<span class="sticker top-right"><span class="labelsale">' . __($saleLabel) .'</span></span>';
        
        return $html;
    }

    protected function isNew($_product)
    {
        return $this->_nowIsBetween($_product->getData('news_from_date'), $_product->getData('news_to_date'));
    }

    protected function isOnSale($_product)
    {
        $specialPrice = number_format($_product->getFinalPrice(), 2);
        $regularPrice = number_format($_product->getPrice(), 2);

        if ($specialPrice != $regularPrice){

            if(is_null($_product->getData('special_to_date')) && is_null($_product->getData('special_from_date'))) return true;
            
            return $this->_nowIsBetween($_product->getData('special_from_date'), $_product->getData('special_to_date'));
        }

        return false;
    }

    protected function _nowIsBetween($fromDate, $toDate)
    {
        if ($fromDate){
            $fromDate = strtotime($fromDate);
            $toDate = strtotime($toDate);
            $now = strtotime(date("Y-m-d H:i:s"));
            
            if ($toDate){
                if ($fromDate <= $now && $now <= $toDate) return true;
            }else {
                if ($fromDate <= $now) return true;
            }
        } else if($toDate) {
            $toDate = strtotime($toDate);
            $now = strtotime(date("Y-m-d H:i:s"));
            if ($now <= $toDate) return true;
        }
        return false;
    }

    public function getPrcents()
    {
        return array(1 => '100%', 2 => '50%', 3 => '33.333333333%', 4 => '25%', 5 => '20%', 6 => '16.666666666%', 7 => '14.285714285%', 8 => '12.5%');
    }

    public function getResponsiveBreakpoints()
    {
        return Responsive::getBreakpoints();
        return array(1921=>'visible', 1920=>'widescreen', 1480=>'desktop', 1200=>'laptop', 992=>'notebook', 768=>'tablet', 576=>'landscape', 481=>'portrait', 361=>'mobile', 1=>'mobile');
    }

    public function getGridStyle($selector=' .products-grid .product-item', $path='alothemes/grid')
    {
        $rtl     = $this->getThemeCfg('rtl');
        $float   = (isset($rtl['enabled']) && $rtl['enabled']) ? 'right' : 'left';
        $styles  = $selector .'{ float: ' . $float .';}';
        $listCfg = $this->getConfig($path);
        $padding = $listCfg['padding'];
        $prcents = $this->getPrcents();
        $breakpoints = $this->getResponsiveBreakpoints(); ksort($breakpoints);
        $total = count($breakpoints);
        $i = $tmp = 1;
        foreach ($breakpoints as $key => $value) {
            $tmpKey = ( $i == 1 || $i == $total) ? $value : current($breakpoints);
            if($i >1){
                $styles .= ' @media (min-width: '. $tmp .'px) and (max-width: ' . ($key-1) . 'px) {' .$selector. '{padding: 0 '.$padding.'px; width: '.$prcents[$listCfg[$value]] .'} ' .$selector. ':nth-child(' .$listCfg[$value]. 'n+1){clear: ' . $float . ';}}';
                next($breakpoints);
            }
            if( $i == $total ) $styles .= ' @media (min-width: ' . $key . 'px) {' .$selector. '{padding: 0 '.$padding.'px; width: '.$prcents[$listCfg[$value]] .'} ' .$selector. ':nth-child(' .$listCfg[$value]. 'n+1){clear: ' . $float . ';}}';
            $tmp = $key;
            $i++;
        }
        return  '<style type="text/css">' .$styles. '</style>';
    }

    public function getConfgRUC($type) // with Type = 'related' || 'upsell' || 'crosssell'
    {
        $data = $this->getConfig('alothemes/' .$type);
        $breakpoints = $this->getResponsiveBreakpoints();
        $total = count($breakpoints);
        if($data['slide']){
            $data['vertical-Swiping'] = $data['vertical'];
            $responsive = '[';
            foreach ($breakpoints as $size => $opt) {
                $responsive .= '{"breakpoint": '.$size.', "settings": {"slidesToShow": '.$data[$opt].'}}';
                $total--;
                if($total) $responsive .= ', ';
            }
            $responsive .= ']';
            $data['slides-To-Show'] = $data['visible'];
            // $data['swipe-To-Slide'] = 'true';
            $data['responsive'] = $responsive;
            $Rm = array('slide', 'visible', 'widescreen', 'desktop', 'laptop', 'notebook', 'tablet', 'landscape', 'portrait', 'mobile'); // require with slick
            foreach ($Rm as $vl) { unset($data[$vl]); }

            return $data;

        } else {
            $options = array();
            $breakpoints = $this->getResponsiveBreakpoints(); ksort($breakpoints);
            foreach ($breakpoints as $size => $screen) {
                $options[]= array($size => $data[$screen]);
            }
            return array('padding' => $data['padding'], 'responsive' =>json_encode($options));
            
            // $prcents = $this->getPrcents();
            // $padding = $data['padding'];
            // $selector = '.' . $type .' .products-grid .product-item';
            // $styles = $selector .'{ float: left;}';
            // $i = $tmp= 1;
            // foreach ($breakpoints as $key => $value) {
            //     $tmpKey = ( $i == 1 || $i == $total ) ? $value : current($breakpoints);
            //     if($i >1){
            //         $styles .= ' @media (min-width: '. $tmp .'px) and (max-width: ' . ($key-1) . 'px) {' .$selector. '{padding: 0 '.$padding.'px; width: '.$prcents[$data[$value]] .'} ' .$selector. ':nth-child(' .$data[$value]. 'n+1){clear: left;}}';
            //         next($breakpoints);
            //     }
            //     if( $i == $total ) $styles .= ' @media (min-width: ' . $key . 'px) {' .$selector. '{padding: 0 '.$padding.'px; width: '.$prcents[$data[$value]] .'} ' .$selector. ':nth-child(' .$data[$value]. 'n+1){clear: left;}}';
            //     $tmp = $key;
            //     $i++;
            // }

            // return '<style type="text/css">' .$styles. '</style>';
        }
    }

    /**
     * @return string
     */
    public function getPageLayout()
    {
        return $this->pageConfig->getPageLayout();
    }

    
    public function getFreeShipping($_product)
    {
        $freeshipping = false;
        $active_freeshipping = $this->scopeConfig->getValue('carriers/freeshipping/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($active_freeshipping)
        {
            $free_shipping_subtotal = $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $price = $_product->getFinalPrice();
            if($price >= $free_shipping_subtotal)
                $freeshipping = true;
        }
        return $freeshipping;
    }
}
