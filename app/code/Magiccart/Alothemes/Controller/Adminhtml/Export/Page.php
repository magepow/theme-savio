<?php

/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-19 18:02:53
 * @@Function:
 */

namespace Magiccart\Alothemes\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;

class Page extends \Magiccart\Alothemes\Controller\Adminhtml\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    public function execute()
    {
        if ($this->getRequest()->getParam('theme_path')) $this->ExportXml();
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }

    public function ExportXml()
    {
        $fileName = 'page.xml';
        $theme_path = $this->getRequest()->getParam('theme_path');
        $exportIds = $this->getRequest()->getParam('exportIds');
        $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
        $filePath = sprintf(self::CMS, $theme_path) . $fileName;
        $blockIds = [];
        $blockCollection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
        foreach ($blockCollection as $block) {
            $blockIds[$block->getId()] = $block->getIdentifier();
        }
        try {
            $collection = $this->_objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
            $collection->addFieldToFilter('page_id', array('in' => $exportIds));
            $options = array('title', 'page_layout', 'identifier', 'content_heading', 'content', 'layout_update_xml', 'is_active');
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<root>';
            $xml .= '<page>';
            $num = 0;
            foreach ($collection as $page) {
                $xml .= '<item>';
                foreach ($options as $opt) {
                    $value = $page->getData($opt);
                    if($opt == 'content'){
                        $value = $this->blockIdToIdentifier($value, $blockIds);
                    }
                    $xml .= '<' . $opt . '><![CDATA[' . $value . ']]></' . $opt . '>';
                }
                $xml .= '</item>';
                $num++;
            }
            $xml .= '</page>';
            $xml .= '</root>'; // $this->_sendUploadResponse($fileName, $xml);
            $dir->writeFile($filePath, '$xml');
            $backupFilePath = $dir->getAbsolutePath($filePath);
            // @mkdir($filePath, 0644, true);
            $doc = new \DOMDocument('1.0', 'UTF-8');
            $doc->loadXML($xml);
            $doc->formatOutput = true;
            $doc->save($backupFilePath);

            $this->messageManager->addSuccess(__('Export (%1) Item(s):', $num));
            $this->messageManager->addSuccess(__('Successfully exported to file "%1"', $backupFilePath));
        } catch (\Exception $e) {
            $this->messageManager->addError(__(
                'Can not save export file "%1".<br />"%2"',
                $filePath,
                $e->getMessage()
            ));
        }
    }

    public function blockIdToIdentifier($content, $blockIds)
    {
        return preg_replace_callback(
            '/block_id="(.*?)"/',
            function($match) use ($blockIds) {
                $id = $match[1];
                if(isset($blockIds[$id]) && $blockIds[$id]){
                    $identifier = $blockIds[$id];
                    $blockId = str_replace($match[1], $identifier, (string) $match[0]);
                    return $blockId;
                    // return 'block_id="' . $identifier . '"';
                }
                return $match[0];
            },
            $content
        );
    }
}
