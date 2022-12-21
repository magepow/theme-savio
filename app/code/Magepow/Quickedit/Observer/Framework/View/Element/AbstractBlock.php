<?php

/**
 * @Author: nguyen
 * @Date:   2020-05-09 23:33:09
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2021-05-31 15:02:25
 */

namespace Magepow\Quickedit\Observer\Framework\View\Element;

use Magento\Framework\Event\ObserverInterface;
use Magepow\Quickedit\Helper\DevHelper;

class AbstractBlock extends DevHelper implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer )
    {
        if ($this->showQuickedit()) {
            $block      = $observer->getData('block');
            $quickedit  = $block->getQuickedit();
            $class      = get_class($block);
            $class      = preg_replace('/\b\\\Interceptor$/', '', $class);

            switch ($class) {
                case 'Magento\Cms\Block\Block':
                case 'Magento\Cms\Block\Widget\Block':
                    $blockId = $block->getData('block_id');
                    if ($blockId) {
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $_block = $objectManager->get('\Magento\Cms\Model\BlockFactory')->create();
                        $storeId = $this->getStore()->getId();
                        $_block->setStoreId($storeId)->load($blockId);
                        $blockId  = $_block->getId();
                        $routeParams = [
                            'block_id' => $blockId
                        ];
                        $editUrl = $this->getAdminUrl('cms/block/edit', $routeParams);
                        $info = [
                            [
                                'title' => __('Admin Panel > Content > Blocks > Id is: %1', $blockId),
                                'url'   => $editUrl
                            ],
                            [
                                'title' => __('Admin Panel > Content > Blocks > Identifier is: %1', $_block->getIdentifier()),
                                'url'   => $editUrl
                            ],
                            [
                                'title' => __('Edit'),
                                'url'   => $editUrl
                            ]
                        ];
                        $quickedit = $quickedit ? array_merge($quickedit, $info) : $info;
                    }
                break;
                case 'Magento\Cms\Block\Page':
                    $page   = $block->getPage();
                    if ($page) {
                        $pagId  = $page->getId();
                        $routeParams = [
                            'page_id' => $pagId
                        ];
                        $editUrl = $this->getAdminUrl('cms/page/edit', $routeParams);
                        $info = [
                            [
                                'title' => __('Admin Panel > Content > Pages > Id is: %1', $pagId),
                                'url'   => $editUrl
                            ],
                            [
                                'title' => __('Admin Panel > Content >Pages > Identifier is: %1', $page->getIdentifier()),
                                'url'   => $editUrl
                            ],
                            [
                                'title' => __('Edit'),
                                'url'   => $editUrl
                            ]
                        ];
                        $quickedit = $quickedit ? array_merge($quickedit, $info) : $info;
                    }
                break;
                case 'Magento\Catalog\Block\Category\View':
                    $category = $block->getCurrentCategory();
                    $routeParams = [
                        'id' => $category->getId()
                    ];
                    $urlEdit = $this->getAdminUrl('catalog/category/edit', $routeParams);
                    $quickedit[] = [
                        'title' => __('Edit Category'),
                        'url'   => $urlEdit
                    ];
                break;
                case 'Magento\Catalog\Block\Product\ProductList\Toolbar':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/catalog') . '#catalog_frontend-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;
                case 'Magento\Swatches\Block\LayeredNavigation\RenderLayered':
                    $data = $block->getSwatchData();
                    if(isset($data['attribute_id'])){
                        $routeParams = [
                            'attribute_id' => $data['attribute_id']
                        ];
                        $urlEdit = $this->getAdminUrl('catalog/product_attribute/edit', $routeParams);
                        $quickedit[] = [
                            'title' => __('Edit Attribute'),
                            'url'   => $urlEdit

                        ];
                    }
                break;

                case 'Magento\Newsletter\Block\Subscribe':
                case 'Magento\Customer\Block\Newsletter':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/newsletter');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\ProductVideo\Block\Product\View\Gallery':
                    $product = $block->getProduct();
                    $routeParams = [
                        'id' => $product->getId()
                    ];
                    $urlEdit = $this->getAdminUrl('catalog/product/edit', $routeParams);
                    $quickedit[] = [
                        'title' => __('Edit Product'),
                        'url'   => $urlEdit
                    ];
                break;

                case 'Magento\Catalog\Block\Product\Image':
                    $routeParams = [
                        'id' => $block->getProductId()
                    ];
                    $urlEdit = $this->getAdminUrl('catalog/product/edit', $routeParams);
                    $quickedit[] = [
                        'title' => __('Edit Product'),
                        'url'   => $urlEdit
                    ];
                break;

                case 'Magento\Checkout\Block\Cart':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/checkout') . '#checkout_cart-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Checkout\Block\Onepage':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/checkout') . '#checkout_options-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Checkout\Block\Cart\Sidebar':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/checkout') . '#checkout_sidebar-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Store\Block\Switcher':
                    $urlEdit = $this->getAdminUrl('system_store/index');
                    $quickedit[] = [
                        'title' => __('Edit Store'),
                        'url'   => $urlEdit
                    ];
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/general') . '#general_locale-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Directory\Block\Currency':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/currency') . '#currency_options-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Customer\Block\Form\Login':
                case 'Magento\Customer\Block\Form\Register':
                case 'Magento\Customer\Block\Account\Dashboard\Info':
                case 'Magento\Customer\Block\Form\Edit':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/customer');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Downloadable\Block\Customer\Products\ListProducts':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/catalog') . '#catalog_downloadable-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Wishlist\Block\Customer\Wishlist':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/wishlist');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Sales\Block\Order\History':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/sales');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Customer\Block\Address\Edit':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/shipping');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Review\Block\Customer\ListCustomer':
                    $urlConfig = $this->getAdminUrl('review/product/index');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magento\Theme\Block\Html\Header\Logo':
                case 'Magento\Theme\Block\Html\Footer':
                    $store = $this->getStore();
                    $routeParams = [
                        'scope_id' => $store->getId()
                    ];
                    $class      = (new \ReflectionClass($class))->getShortName();
                    $urlEdit = $this->getAdminUrl('theme/design_config/edit/scope/stores', $routeParams);
                    $quickedit[] = [
                        'title' => __('Edit %1', $class),
                        'url'   => $urlEdit
                    ];
                break;

                /* Magiccart */
                case 'Magento\Catalog\Block\Product\ProductList\Crosssell':
                case 'Magento\Checkout\Block\Cart\Crosssell':
                case 'Magento\Catalog\Block\Product\ProductList\Related':
                case 'Magento\Catalog\Block\Product\ProductList\Upsell':
                case 'Magiccart\Alothemes\Block\Newsletter':
                case 'Magiccart\Alothemes\Block\RecentOrder':
                    $name = explode('\\', $class);
                    $name = array_pop($name);
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/alothemes') . '#alothemes_' . strtolower($name) . '-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                    $product = $block->getProduct();
                    if($product){
                        $routeParams = [
                            'id' => $product->getId()
                        ];
                        $urlEdit = $this->getAdminUrl('catalog/product/edit', $routeParams);
                        $quickedit[] = [
                            'title' => __('Edit Product'),
                            'url'   => $urlEdit
                        ];
                    }
                break;

                case 'Magiccart\Alothemes\Block\Social':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/alosocial') . '#alosocial_social-link';
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magiccart\Magicmenu\Block\Menu':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/magicmenu');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magiccart\Magicproduct\Block\Widget\Product':
                case 'Magiccart\Magicproduct\Block\Widget\Category':
                case 'Magiccart\Magicproduct\Block\Widget\Catalog':
                    if (!$quickedit) {
                        $magicproduct = $block->getMagicproduct();
                        $class        = (new \ReflectionClass($class))->getShortName();
                        if (!$magicproduct) {
                            $type = [
                                'Product'   => '1',
                                'Category'  => '2',
                                'Catalog'   => '3',
                            ];
                            $identifier          = $block->getIdentifier();
                            $magicproductFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('Magiccart\Magicproduct\Model\MagicproductFactory');
                            $magicproduct        = $magicproductFactory->create()->getCollection( $identifier, 'identifier')
                                                                                ->addFieldToFilter('identifier', $identifier)
                                                                                ->addFieldToFilter('type_id', $type[$class])
                                                                                ->setPageSize(1)
                                                                                ->getFirstItem();
                        }
                        if($magicproduct){
                            $id = $magicproduct->getId();
                            $routeParams = [
                                'magicproduct_id' => $id
                            ];
                            $adminPath  = 'magicproduct/' . strtolower($class) . '/edit';
                            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
                            $moduleName = 'Magiccart_Magicproduct';
                            $moduleName = str_replace('_', ' > ', $moduleName);
                            $quickedit  = [
                                [
                                    'title' => __('Admin Panel > %1 > %2 Id is: %3', $moduleName, $class, $id),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Admin Panel > %1 > %2 Identifier is: %3', $moduleName, $class, $magicproduct->getIdentifier()),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Edit'),
                                    'url'   => $editUrl
                                ]
                            ];
                        }
                    }

                break;

                case 'Magiccart\Magicslider\Block\Widget\Slider':
                    if (!$quickedit) {
                        $magicslider   = $block->getMagicslider();
                        if (!$magicslider) {
                            $identifier         = $block->getIdentifier();
                            $magicsliderFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('Magiccart\Magicslider\Model\MagicsliderFactory');
                            $magicslider        = $magicsliderFactory->create()->load( $identifier, 'identifier');
                        }
                        if($magicslider){
                            $id = $magicslider->getId();
                            $routeParams = [
                                'magicslider_id' => $id
                            ];
                            $class      = (new \ReflectionClass($class))->getShortName();
                            $adminPath  = 'magicslider/index/edit';
                            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
                            $moduleName = 'Magiccart_Magicslider';
                            $moduleName = str_replace('_', ' > ', $moduleName);
                            $quickedit  = [
                                [
                                    'title' => __('Admin Panel > %1 > %2 Id is: %3', $moduleName, $class, $id),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Admin Panel > %1 > %2 Identifier is: %3', $moduleName, $class, $magicslider->getIdentifier()),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Edit'),
                                    'url'   => $editUrl
                                ]
                            ];
                        }
                    }

                break;

                case 'Magiccart\Lookbook\Block\Widget\Product':
                    if (!$quickedit) {
                        $lookbook = $block->getLookbook();
                        if($lookbook){
                            $routeParams = [
                                // 'lookbook_id' => $id
                            ];
                            $class      = 'Product'; //basename(__FILE__, ".php");
                            $adminPath  = 'lookbook/' . strtolower($class) . '/index';
                            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
                            $configUrl  = $this->getAdminUrl('adminhtml/system_config/edit/section/lookbook');
                            $moduleName = 'Magiccart_Lookbook';
                            $moduleName = str_replace('_', ' > ', $moduleName);
                            $quickedit  = [
                                [
                                    'title' => __('Admin Panel > %1 > %2 :', $moduleName, $class),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Admin Panel > System > Stores > Configuration > Magiccart > Lookbook'),
                                    'url'   => $configUrl
                                ],
                                [
                                    'title' => __('Edit'),
                                    'url'   => $editUrl
                                ]
                            ];
                        }
                    }

                break;

                case 'Magiccart\Shopbrand\Block\Widget\Brand':
                    if (!$quickedit) {
                        $brands = $block->getBrands();
                        if($brands){
                            $routeParams = [
                                // 'shopbrand_id' => $id
                            ];
                            $class      = 'Brand'; //basename(__FILE__, ".php");
                            $adminPath  = 'shopbrand/' . strtolower($class) . '/index';
                            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
                            $configUrl  = $this->getAdminUrl('adminhtml/system_config/edit/section/shopbrand');
                            $moduleName = 'Magiccart_Shopbrand';
                            $moduleName = str_replace('_', ' > ', $moduleName);
                            $quickedit  = [
                                [
                                    'title' => __('Admin Panel > %1 > %2 :', $moduleName, $class),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Admin Panel > System > Stores > Configuration > Magiccart > Shop Brand'),
                                    'url'   => $configUrl
                                ],
                                [
                                    'title' => __('Edit'),
                                    'url'   => $editUrl
                                ]
                            ];
                        }
                    }

                break;

                case 'Magiccart\Testimonial\Block\Widget\Testimonial':
                    if (!$quickedit) {
                        $testimonials = $block->getTestimonials();
                        if($testimonials){
                            $routeParams = [
                                // 'testimonial_id' => $id
                            ];
                            $class      = 'Testimonial'; //basename(__FILE__, ".php");
                            $adminPath  = 'testimonial/' . strtolower($class) . '/index';
                            $editUrl    = $this->getAdminUrl($adminPath, $routeParams);
                            $configUrl  = $this->getAdminUrl('adminhtml/system_config/edit/section/testimonial');
                            $moduleName = 'Magiccart_Testimonial';
                            $moduleName = str_replace('_', ' > ', $moduleName);
                            $quickedit  = [
                                [
                                    'title' => __('Admin Panel > %1 > %2 :', $moduleName, $class),
                                    'url'   => $editUrl
                                ],
                                [
                                    'title' => __('Admin Panel > System > Stores > Configuration > Magiccart > Testimonial'),
                                    'url'   => $configUrl
                                ],
                                [
                                    'title' => __('Edit'),
                                    'url'   => $editUrl
                                ]
                            ];
                        }
                    }

                break;

                /* Magepow */
                case 'Magepow\Gdpr\Block\Customer\Button':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/gdpr');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magepow\Layerednav\Block\Layerednav':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/layered_ajax');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

                case 'Magepow\Ajaxcart\Block\Ajax\Template':
                    $urlConfig = $this->getAdminUrl('adminhtml/system_config/edit/section/magepow_ajaxcart');
                    $quickedit[] = [
                        'title' => __('Config'),
                        'url'   => $urlConfig

                    ];
                break;

            }

            if ($quickedit){
                $transport    = $observer->getData('transport');
                $html               =  $transport->getHtml();
                $html               = $this->renderHints($html, $quickedit); 
                $transport->setHtml($html);               
            }

        }
        
        return $this;
    }

}
