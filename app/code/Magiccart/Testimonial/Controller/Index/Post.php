<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2019-05-27 08:40:51
 * @@Modify Date: 2019-05-27 08:48:48
 * @@Function:
 */

namespace Magiccart\Testimonial\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;

class Post extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Context
     */
    private $context;
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    protected $_moduleHelper;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
  
     */
    
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\App\Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magiccart\Testimonial\Helper\Data $moduleHelper
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_moduleHelper = $moduleHelper;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        if ($data = $this->getRequest()->getPostValue()) {
            
            if (isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {
                /*
                 * Save image upload
                 */
                $model = $this->_objectManager->create('Magiccart\Testimonial\Model\Testimonial');
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                    $uploader->addValidateCallback('testimonial_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath('magiccart/testimonial/')
                    );
                    
                    $data['image'] = 'magiccart/testimonial/'.$result['file'];

                    $autoApprove = $this->_moduleHelper->getGeneralCfg('autoApprove');
                    if(!$autoApprove)
                        $data['status'] = 2;
                    else
                        $data['status'] = 1;

                    /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate */
                    $localeDate = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
                    $data['created_time'] = $localeDate->date()->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
                    $data['update_time'] = $localeDate->date()->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
                    $model->setData($data);
                    
                    $model->save();
                    $this->messageManager->addSuccessMessage('The testimonial has been completed');
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            }
            // $this->_redirect('*/*');
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    // private function validatedParams()
    // {
    //     $request = $this->getRequest();
    //     if (trim($request->getParam('name')) === '') {
    //         throw new LocalizedException(__('Name is missing'));
    //     }
    //     if (trim($request->getParam('comment')) === '') {
    //         throw new LocalizedException(__('Comment is missing'));
    //     }
    //     if (false === \strpos($request->getParam('email'), '@')) {
    //         throw new LocalizedException(__('Invalid email address'));
    //     }
    //     if (trim($request->getParam('hideit')) !== '') {
    //         throw new \Exception();
    //     }

    //     return $request->getParams();
    // }
}