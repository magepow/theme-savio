<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-05-16 17:02:46
 * @@Function:
 */

namespace Magiccart\Alothemes\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magiccart\Alothemes\Controller\Adminhtml\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        
        if($this->getRequest()->getParam('theme_path')) $this->ExportXml();

        return $resultRedirect->setPath('*/*/system');
    }

    public function ExportXml()
    {
        $fileName = 'system.xml';
        $theme_path = $this->getRequest()->getParam('theme_path');
        $store = $this->_storeManager->getStore($this->getRequest()->getParam('store'));
        $moduleList = $this->_objectManager->create('\Magiccart\Alothemes\Model\Export\Module');
        $modules = $moduleList->toArray();
        $moduleDir =  $this->_objectManager->create('\Magento\Framework\Module\Dir');
        $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
        $filePath = sprintf(self::CMS, $theme_path) .$fileName;

        try{

                $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                $xml .= '<root>';
                $xml .= '<system>';
                $num = 0;
                foreach ($modules as $module) {
                    $etc = $moduleDir->getDir($module, 'etc');
                    $systemXml = $etc. DIRECTORY_SEPARATOR . 'adminhtml' . DIRECTORY_SEPARATOR . 'system.xml';
                    $configXml = $etc. DIRECTORY_SEPARATOR . 'config.xml';
                    if(file_exists($systemXml)){

                        $cfgXmlObj = new \Magento\Framework\Simplexml\Config($configXml);
                        $default = $cfgXmlObj->getNode('default')->asArray();

                        $sysXmlObj = new \Magento\Framework\Simplexml\Config($systemXml);
                        $sections = $sysXmlObj->getNode('system')->children();

                        foreach ($sections as $tmp) {
                            if($tmp->getName() != 'section') continue;
                            $section = $tmp->getAttribute('id');
                            $collection = $this->_scopeConfig->getValue($section, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                            if(!$collection) continue;
                            foreach ($collection as $key => $group) {
                                if(is_array($group)){
                                    foreach ($group as $k => $vl) {

                                        if(isset($default[$section][$key][$k])){
                                            if($vl == $default[$section][$key][$k]) continue;
                                        }else {
                                            if($vl == '') continue;
                                        }

                                        $xml .= '<config>';
                                        $xml .= '<path>' . $section .'/'. $key .'/'. $k . '</path>';
                                        $xml .= '<value><![CDATA[' . $vl . ']]></value>'; //$xml .= '<value>' . $vl . '</value>';
                                        $xml .= '</config>';
                                        $num++;

                                    }
                                }
                            }
                        }

                    }
                }

                $extraCfg = array(
                    // 'design/theme/theme_id',
                    'web/default/front',
                    'web/default/cms_home_page',
                    'web/default/cms_no_route',
                );

                foreach ($extraCfg as $cfg) {
                    $vl = $this->_scopeConfig->getValue($cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                    $xml .= '<config>';
                    $xml .= '<path>' . $cfg . '</path>';
                    $xml .= '<value>' . $vl . '</value>';
                    $xml .= '</config>';
                    $num++;
                }

                $xml .= '</system>';

                $themeId = $this->_scopeConfig->getValue('design/theme/theme_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                // $themesCollections = $this->_objectManager->create('Magento\Theme\Model\Theme\Collection');
                // foreach ($themesCollections->loadData() as $value) {
                //     var_dump($value->getData());die;
                // }
                $theme = $this->_objectManager->create('Magento\Theme\Model\Theme')->load($themeId);
                $xml .= '<theme>' . $theme->getData('theme_path') . '</theme>';
                $xml .= '</root>';

               // $this->_sendUploadResponse($fileName, $xml);
                $dir->writeFile($filePath, '$xml');
                $backupFilePath = $dir->getAbsolutePath($filePath);
                // @mkdir($filePath, 0644, true);
                $doc =  new \DOMDocument('1.0', 'UTF-8');
                $doc->loadXML($xml);
                $doc->formatOutput = true;
                $doc->save($backupFilePath);

                $this->messageManager->addSuccess(__('Export (%1) Item(s):', $num));
                $this->messageManager->addSuccess(__('Successfully exported to file "%1"',$backupFilePath));
        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not save export file "%1".<br/>"%2"', $filePath, $e->getMessage()));
        }
    }
}
