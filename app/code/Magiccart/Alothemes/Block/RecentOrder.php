<?php

/**
 * @Author: nguyen
 * @Date:   2019-01-20 12:52:42
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-05-21 12:09:58
 */

namespace Magiccart\Alothemes\Block;

class RecentOrder extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface
{
    const DEFAULT_CACHE_TAG = 'ALOTHEMES_RECENTORDER';

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_objectManager;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var _stockconfig
     */
    protected $_stockConfig;

     /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    
    protected $_limit; // Limit Product
    protected $_orderInfo; // Limit Product

    public $_scopeConfig;
    public $_recentConfig;

    /**
     * @param Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\CatalogInventory\Model\Configuration $stockConfig,
        array $data = []
    ) {
        $this->urlHelper = $urlHelper;
        $this->_objectManager = $objectManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_stockFilter = $stockFilter;
        $this->_stockConfig = $stockConfig;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_recentConfig = $this->_scopeConfig->getValue( 'alothemes/recentorder', \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        parent::__construct( $context, $data );
    }

    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 86400;
    }

    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $keyInfo[]   =  $this->_storeManager->getStore()->getStoreId();
        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::DEFAULT_CACHE_TAG, self::DEFAULT_CACHE_TAG . '_' . $this->_storeManager->getStore()->getStoreId()];
    }

    public function getTypeFilter()
    {
        return 'RecentOrder';
    }

    public function getFrontendCfg()
    {
        return [
            'autoplay',
            'firsttime',
            'close_off',
            'speed',
        ];
    }

    public function getWidgetCfg($cfg=null)
    {
        $info = $this->_recentConfig;
        if($info){
            if(isset($info[$cfg])) return $info[$cfg];
            return $info;          
        }else {
            $info = $this->getCfg();
            if(isset($info[$cfg])) return $info[$cfg];
            return $info;
        }
    }

    public function getLoadedProductCollection()
    {
        $this->_limit = (int) $this->getWidgetCfg('limit');
        $type = $this->getTypeFilter();
        $fn = 'get' . ucfirst($type);
        $collection = $this->{$fn}();
        if ($this->_stockConfig->isShowOutOfStock() != 1) {
            $this->_stockFilter->addInStockFilterToCollection($collection);
        }

        $collection->setPageSize($this->_limit)->setCurPage(1);

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );

        return $collection;
    }


    public function getRecentOrder(){

        $producIds = array();

        if(isset($this->_recentConfig['fakeinfo']) && $this->_recentConfig['fakeinfo']){
            if(isset($this->_recentConfig['product_ids']) && $this->_recentConfig['product_ids']){
                $producIds = $this->_recentConfig['product_ids'];
                $producIds = explode(',',$producIds);
                $faketime = explode(',', $this->_recentConfig['faketime']);
                $fakeaddress = explode(',', $this->_recentConfig['fakeaddress']);
                foreach ( $producIds as $key => $id ) {
                    $info = array();
                    $info['time'] = isset($faketime[$key]) ? $faketime[$key]: $faketime[array_rand($faketime)];
                    $address = isset($fakeaddress[$key]) ? $fakeaddress[$key]: $fakeaddress[array_rand($fakeaddress)];
                    $info['address'] = __('from %1', $address);
                    $this->_orderInfo[$id] = $info;
                    # code...
                }
            }
        } else {
            $ordercollection = $this->_objectManager->get('Magento\Sales\Model\Order')->getCollection()
                                                                ->addFieldToSelect(array('*'))
                                                                ->setOrder('entity_id','DESC')
                                                                ->setPageSize($this->_limit*5)
                                                                ->setCurPage(1);

            $i = 0;
            foreach ($ordercollection as $orderDatamodel) {
                $orderId   =   $orderDatamodel->getId();
                $shippingAddress  = $orderDatamodel->getShippingAddress();
                $info       = array();
                if($shippingAddress){
                    $city       = $shippingAddress->getCity();
                    $country    = $shippingAddress->getData('country_id');
                    $info['address'] = __('from %1, %2', $city, $country);
                }
                $order = $this->_objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
                $orderItems = $order->getAllVisibleItems();
                foreach ($orderItems as $item) {
                    $productId = $item->getProductId();
                    if(!in_array($productId, $producIds)){
                        $id = $item->getProductId();
                        $producIds[]    =   $id;
                        $info['time']   = $item->getData('created_at');
                        $this->_orderInfo[$id] = $info;
                        $i ++;
                        if($i >= $this->_limit) break;
                    } 

                }
                if($i >= $this->_limit) break;
            }
        }

        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter('entity_id', array('in' => $producIds));

        return $collection;
        
    }

    public function getInfoPurchased(\Magento\Catalog\Model\Product $product)
    {
        $productId = $product->getId();
        $info = array();
        if( isset( $this->_orderInfo[ $productId ] ) ){
            $info = $this->_orderInfo[ $productId ];
        }
        return $info;
    }

    public function getInfoTime($time)
    {
        if(isset($this->_recentConfig['fakeinfo']) && $this->_recentConfig['fakeinfo']){
            return $time;
        }

        return $this->time_elapsed_string($time);
    }

    public function time_elapsed_string($datetime, $full = false) {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => __('year'),
            'm' => __('month'),
            'w' => __('week'),
            'd' => __('day'),
            'h' => __('hour'),
            'i' => __('minute'),
            's' => __('second'),
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ' . __('ago') : __('just now');
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

}
