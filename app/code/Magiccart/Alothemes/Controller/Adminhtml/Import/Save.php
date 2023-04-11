<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-09-15 14:52:26
 * @@Function:
 */

namespace Magiccart\Alothemes\Controller\Adminhtml\Import;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magiccart\Alothemes\Controller\Adminhtml\Action
{


    protected $_store = 0;
    protected $themePath;
    protected $_filePath = '';
    protected $_dir = '';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();

        $this->themePath      = $this->getRequest()->getParam('theme_path');
        if($this->themePath){
            $this->_filePath = sprintf(self::CMS, $this->themePath);
            $this->importXml();
        }

        return $resultRedirect->setPath('*/*/index');
    }

    public function getImportFile($fileName)
    {
        $filePath = $this->_filePath .$fileName;
        $backupFilePath = $this->_dir->getAbsolutePath($filePath);

        if(is_readable($backupFilePath)) return $backupFilePath;

        $parent = preg_replace( '/[0-9]/', '', $this->themePath );

        $backupFilePath = str_replace($this->themePath, $parent, $backupFilePath);

        if(is_readable($backupFilePath)) return $backupFilePath;

        $backupFilePath = str_replace($parent, $parent . '1', $backupFilePath);

        if(is_readable($backupFilePath)) return $backupFilePath;

        $backupFilePath = str_replace($parent . '1', 'Alothemes/default', $backupFilePath);

        return $backupFilePath;
    }

    public function importXml()
    {
        $this->_dir      = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
        $request = $this->getRequest()->getParams();
        $stores = isset($request['store_ids']) ? $request['store_ids'] : array(0);
        $scope  = 'default';
        if(isset($request['scope']) && isset($request['scope_id'])){
            $scope = $request['scope'];
            if($request['scope'] == 'websites'){
                $stores = $this->_storeManager->getWebsite($request['scope_id'])->getStoreIds();
            }else {
                $stores  = $request['scope_id']; 
            }
        }
        $this->_store = is_array($stores) ? $stores : explode(',', (string) $stores);
        $blockIds = []; 
        if($request['action']){
            if( isset($request['block']) && $request['block'] ){
                $blockIds = $this->importBlock(isset($request['overwrite_block']));
                // var_dump($blockIds);die('ssxx');
                if(!empty($blockIds)){
                    $blockCollection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
                    $blockCollection->addFieldToFilter('block_id', ['in'=> array_keys($blockIds)]);
                    foreach ($blockCollection as $block) {
                        $content = $block->getData('content');
                        $content = $this->IdentifierToBlockId($content, $blockIds);
                        $block->setData('content', $content);
                        $block->save();
                    }
                }
            }
            if( isset($request['page'])  && $request['page'] ){
                $pageIds = $this->importPage(isset($request['overwrite_page']));
                if(!empty($blockIds) && !empty($pageIds)){
                    $pageCollection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
                    $pageCollection->addFieldToFilter('page_id', ['in'=> array_keys($pageIds)]);
                    foreach ($pageCollection as $page) {
                        $content = $page->getData('content');
                        $content = $this->IdentifierToBlockId($content, $blockIds);
                        $page->setData('content', $content);
                        $page->save();
                    }
                }
            }
            if( isset($request['config'])  && $request['config'] )              $this->importSystem($scope);
            if( isset($request['magicmenu']) && $request['magicmenu'] )         $this->importMagicmenu();
            if( isset($request['magicslider']) && $request['magicslider'] )     $this->importMagicslider(); 
            if( isset($request['magicproduct']) && $request['magicproduct'] )   $this->importMagicproduct(isset($request['magicproduct_conditions']));
            if( isset($request['lookbook']) && $request['lookbook'] )           $this->importLookbook();
        } else {
            if( isset($request['block']) && $request['block'] )   $this->removeBlock();
            if( isset($request['page'])  && $request['page'] )    $this->removePage();
        }

    }
    public function removeBlock()
    {
        $fileName = 'block.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $block = $xmlObj->getNode('block');
            if($block){
                foreach ($block->children() as $item){
                    //Check if Block already exists
                    $collection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
                    $oldBlocks = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);
                    
                    if (count($oldBlocks) > 0){
                        foreach ($oldBlocks as $old) $old->delete();
                        $num++;
                    }

                }               
            }

            $this->messageManager->addSuccess(__('Remove (%1) Block(s) in system from file "%2".', $num, $backupFilePath));  

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not remove Block(s) in system from file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    public function removePage()
    {
        $fileName = 'page.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $page = $xmlObj->getNode('page');
            if($page){
                foreach ($page->children() as $item){
                    //Check if Block already exists
                    $collection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
                    $oldPages = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);
                    
                    if (count($oldPages) > 0){
                        foreach ($oldPages as $old) $old->delete();
                        $num++;
                    }
                }               
            }

            $this->messageManager->addSuccess(__('Remove (%1) Page(s) in system from file "%2".', $num, $backupFilePath));  

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not remove Page(s) in system from file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }        
    }

    public function importBlock($overwrite=false)
    {
        $fileName = 'block.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        $blockIds = [];
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $block = $xmlObj->getNode('block');
            if($block){
                foreach ($block->children() as $item){
                    $model = $this->_objectManager->create('Magento\Cms\Model\Block');
                    //Check if Block already exists
                    $collection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
                    if(in_array('0', $storeIds)){
                        $oldBlocks = $collection->addFieldToFilter('identifier', $item->identifier);
                    } else {
                        $oldBlocks = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);
                    }
                    
                    //If items can be overwritten
                    $conflictingOldItems[] = $item->identifier;
                    if (count($oldBlocks) > 0){
                        $conflictingOldItems[] = $item->identifier;
                        foreach ($oldBlocks as $old){
                            if ($overwrite){
                                $model->load($old->getId());
                                $model->addData($item->asArray())->save();
                                $num++;
                                $blockIds[$model->getId()] = (string) $item->identifier;
                                // $this->messageManager->addNotice(__('Import overwrite Block Id (%1) Item(s) and Identifier "%2".', $old->getId(), $old->getIdentifier()));
                            } else {
                                $this->messageManager->addNotice(__('Break overwrite Block Id (%1) Item(s) and Identifier "%2".', $old->getId(), $old->getIdentifier()));  
                            }
                        }
                        continue;
                    }

                    $model = $this->_objectManager->create('Magento\Cms\Model\Block');
                    $model->setData($item->asArray())->setStores($storeIds)->save();
                    $blockIds[$model->getId()] = (string) $item->identifier;
                    $num++;
                }               
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));  

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }

        return $blockIds;
    }

    public function importPage($overwrite=false)
    {
        $fileName = 'page.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        $pageIds = [];
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $page = $xmlObj->getNode('page');
            $conflictingOldItems = [];
            if($page){
                foreach ($page->children() as $item){
                    $model = $this->_objectManager->create('Magento\Cms\Model\Page');
                    //Check if Block already exists
                    $collection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
                    if(in_array('0', $storeIds)){
                        $oldPages = $collection->addFieldToFilter('identifier', $item->identifier);
                    }else {
                        $oldPages = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);
                    }

                    if (count($oldPages) > 0) {
                        $conflictingOldItems[] = $item->identifier;
                        foreach ($oldPages as $old){
                            if ($overwrite){ //If items can be overwrittenz
                                $model->load($old->getId());
                                $model->addData($item->asArray())->save();
                                $pageIds[$model->getId()] = (string) $item->identifier;
                                $num++; 
                                // $this->messageManager->addNotice(__('Import overwrite Page Id (%1) Item(s) and Identifier "%2".', $old->getId(), $old->getIdentifier()));
                            } else {
                                $this->messageManager->addNotice(__('Break overwrite Page Id (%1) Item(s) and Identifier "%2".', $old->getId(), $old->getIdentifier()));
                            }
                        }
                        continue;
                    }
            
                    $model->setData($item->asArray())->setStores($storeIds)->save();
                    $pageIds[$model->getId()] = (string) $item->identifier;
                    $num++;
                }               
            }
            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
        
        return $pageIds;
    }

    public function importSystem($scope='default')
    {
        $fileName = 'system.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $system = $xmlObj->getNode('system');
            if($system){
                $model = $this->_objectManager->create('Magento\Config\Model\ResourceModel\Config');
                $request = $this->getRequest()->getParams();
                foreach ($system->children() as $item){
                    $node = $item->asArray();
                    if(!is_array($storeIds)) $storeIds = array($storeIds);
                    foreach ($storeIds as $storeId) {
                        if(isset($request['usewebsite'])){
                            $oldConfig = $this->_scopeConfig->getValue( $node['path'], $scope, $storeId );
                            if($oldConfig == $node['value']) continue;                           
                        }
                        $model->saveConfig($item->path, $node['value'], $scope, $storeId);
                        $num++;
                    }  
                }              
            }

            $themePath = $xmlObj->getNode('theme');
            $themeId = 0;
            $collection = $this->_objectManager->create('Magento\Theme\Model\Theme')->getCollection();
            foreach ($collection as $item) {
                if($themePath == $item->getData('theme_path')){
                    $themeId = $item->getData('theme_id');
                    break;
                } 
            }
            if($themeId){
                if(is_array($storeIds)){
                    foreach ($storeIds as $storeId) {
                        $model->saveConfig('design/theme/theme_id', $themeId, $scope, $storeId);
                        $num++;
                    }
                } else {
                    $model->saveConfig('design/theme/theme_id', $themeId, $scope, $storeIds);
                    $num++;
                }
      
            }
            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));             

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
        
    }

    public function importMagicmenu()
    {
        $fileName = 'magicmenu.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)){
                // $this->messageManager->addWarning(__("Can't read data file: %1", $backupFilePath));
                $this->messageManager->addNotice(__("No import  %1", $backupFilePath));
                return;
            }
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $magicmenu = $xmlObj->getNode('magicmenu');
            if($magicmenu){
                foreach ($magicmenu->children() as $item){
                    //Check if Extra Menu already exists
                    $collection = $this->_objectManager->create('Magiccart\Magicmenu\Model\ResourceModel\Magicmenu\Collection');
                    $oldMenus   =  $collection->addFieldToFilter('link', $item->link)->load();
                    //If items can be overwritten
                    $overwrite = false; // get in cfg
                    if ($overwrite){
                        if (count($oldMenus) > 0){
                            foreach ($oldMenus as $old) $old->delete();
                        }
                    }else {
                        if (count($oldMenus) > 0){
                            continue;
                        }
                    }
                    $model = $this->_objectManager->create('Magiccart\Magicmenu\Model\Magicmenu');
                    $model->setData($item->asArray())->setStores(implode(',', $storeIds))->save();
                    $num++;
                }               
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));              

        } catch (\Exception $e) {
            $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    public function importMagicproduct($conditions=false)
    {
        $fileName = 'magicproduct.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)){
                // $this->messageManager->addWarning(__("Can't read data file: %1", $backupFilePath));
                $this->messageManager->addNotice(__("No import  %1", $backupFilePath));
                return;
            }
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $magicproduct = $xmlObj->getNode('magicproduct');
            if($magicproduct){
                foreach ($magicproduct->children() as $item){
                    //Check if Magicproduct already exists
                    $collection = $this->_objectManager->create('Magiccart\Magicproduct\Model\ResourceModel\Magicproduct\Collection');
                    $oldMenus   =  $collection->addFieldToFilter('identifier', $item->identifier)->addFieldToFilter('type_id', $item->type_id)->load();
                    //If items can be overwritten
                    $overwrite = false; // get in cfg
                    if ($overwrite){
                        if (count($oldMenus) > 0){
                            foreach ($oldMenus as $old) $old->delete();
                        }
                    }else {
                        if (count($oldMenus) > 0){
                            continue;
                        }
                    }
                    $model = $this->_objectManager->create('Magiccart\Magicproduct\Model\Magicproduct');
                    if($conditions){
                        $model->setData($item->asArray())->save();
                    } else {
                        $dataXml = $item->asArray();
                        if(isset($dataXml['config']) && $dataXml['config']){
                            $config  = unserialize($dataXml['config']);
                            if(isset($config['parameters'])) unset($config['parameters']);
                            $dataXml['config'] = serialize($config);
                        }
                        $model->setData($dataXml)->save();                       
                    }

                    $num++;
                }               
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    public function importMagicslider()
    {
        $fileName = 'magicslider.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)){
                // $this->messageManager->addWarning(__("Can't read data file: %1", $backupFilePath));
                $this->messageManager->addNotice(__("No import  %1", $backupFilePath));
                return;
            }
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $magicproduct = $xmlObj->getNode('magicslider');
            if($magicproduct){
                foreach ($magicproduct->children() as $item){
                    //Check if Magicproduct already exists
                    $collection = $this->_objectManager->create('Magiccart\Magicslider\Model\ResourceModel\Magicslider\Collection');
                    $oldMenus   =  $collection->addFieldToFilter('identifier', $item->identifier)->load();
                    //If items can be overwritten
                    $overwrite = false; // get in cfg
                    if ($overwrite){
                        if (count($oldMenus) > 0){
                            foreach ($oldMenus as $old) $old->delete();
                        }
                    }else {
                        if (count($oldMenus) > 0){
                            continue;
                        }
                    }
                    $model = $this->_objectManager->create('Magiccart\Magicslider\Model\Magicslider');   
                    $model->setData($item->asArray())->save();
                    $num++;
                }               
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    public function importLookbook()
    {
        $fileName = 'lookbook.xml';
        $backupFilePath = $this->getImportFile($fileName);
        $storeIds = $this->_store;
        try{
            if (!is_readable($backupFilePath)){
                // $this->messageManager->addWarning(__("Can't read data file: %1", $backupFilePath));
                $this->messageManager->addNotice(__("No import  %1", $backupFilePath));
                return;
            }
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $magicproduct = $xmlObj->getNode('lookbook');
            if($magicproduct){
                foreach ($magicproduct->children() as $item){
                    //Check if Magicproduct already exists
                    $collection = $this->_objectManager->create('Magiccart\Lookbook\Model\ResourceModel\Lookbook\Collection');
                    $oldMenus   =  $collection->addFieldToFilter('identifier', $item->identifier)->load();
                    //If items can be overwritten
                    $overwrite = false; // get in cfg
                    if ($overwrite){
                        if (count($oldMenus) > 0){
                            foreach ($oldMenus as $old) $old->delete();
                        }
                    }else {
                        if (count($oldMenus) > 0){
                            continue;
                        }
                    }
                    $model = $this->_objectManager->create('Magiccart\Lookbook\Model\Lookbook');   
                    $model->setData($item->asArray())->save();
                    $num++;
                }               
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    public function IdentifierToBlockId($content, $blockIds)
    {
        return preg_replace_callback(
            '/block_id="(.*?)"/',
            function($match) use ($blockIds) {
                $identifier = $match[1];
                $id = array_search($identifier, $blockIds);
                if($id){
                    $blockId = str_replace($match[1], $id, (string) $match[0]);
                    return $blockId;
                    // return 'block_id="' . $id . '"';
                }
                return $match[0];
            },
            $content
        );
    }

}
