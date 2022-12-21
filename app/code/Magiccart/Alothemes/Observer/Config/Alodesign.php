<?php

namespace Magiccart\Alothemes\Observer\Config;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Alodesign implements ObserverInterface
{
	private $request;
    /**
     * @var Filesystem
     */
    protected $fileSystem;
    protected $directorySaticView;
    protected $storeId;

 	public $_helper;
    public $cssFile = '_cache/merged/stores/%s/alothemes_custom.css';

	public function __construct(
		Filesystem $fileSystem,
		RequestInterface $request,
		\Magiccart\Alothemes\Helper\Data $_helper
	) {
		$this->request 		= $request;
		$this->directorySaticView 	= $fileSystem->getDirectoryWrite(DirectoryList::STATIC_VIEW);
		$this->_helper 		= $_helper;
	}
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		//$observer->getWebsite();
		//$observer->getStore(); 

		$cache = '_cache' . DIRECTORY_SEPARATOR . 'merged' . DIRECTORY_SEPARATOR . 'stores';
		$this->directorySaticView->delete($cache);
        $this->storeId = $this->request->getParam('store', 1);
		$isDeveloperColor = $this->_helper->getConfig('alodesign/general/developer', $this->storeId);
		if($isDeveloperColor){
            $this->cssFile = sprintf($this->cssFile, $this->storeId);
			$this->createAsset();
		}

		return $this;
	}

    public function createAsset()
    {
        $cssFilePath = $this->directorySaticView->getAbsolutePath($this->cssFile);
        if(!$this->directorySaticView->isExist($cssFilePath)){
            try {
                $css = $this->_helper->generalCss();
                $this->directorySaticView->writeFile($this->cssFile, $css);
            } catch (FileSystemException $e) {
                $this->cssFile = '';
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
          
        }

    }

}