<?php
/**
 * @Author: nguyen
 * @Date:   2021-07-15 14:01:01
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-07-15 17:55:30
 */

namespace Magiccart\Alothemes\Helper;

use \Magento\Framework\App\Filesystem\DirectoryList;

class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $imageFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    protected $directoryRed;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\Filesystem $filesystem
    )
    {
        parent::__construct($context);
        $this->imageFactory = $imageFactory;
        $this->filesystem   = $filesystem;
        $this->directoryRed = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    public function getImageInfo($image, $field='image')
    {
        if(is_object($image)){
            $img = $image->getData($field);
            if(!$img) return $image;
        } else {
            $img = $image;
        }
        $_image  = $this->imageFactory->create();
        $absPath = $this->directoryRed->getAbsolutePath() . str_replace('/pub/media/', '',  $img);
        if(file_exists($absPath) ){
            $_image->open($absPath);
            return $_image;
        }

        return $image;
    }

}
