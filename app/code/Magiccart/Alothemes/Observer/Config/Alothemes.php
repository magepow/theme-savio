<?php

namespace Magiccart\Alothemes\Observer\Config;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Alothemes implements ObserverInterface
{
	private $request;
	private $configWriter;
    /**
     * @var Filesystem
     */
    protected $fileSystem;
    protected $directory;

	public function __construct(
		Filesystem $fileSystem,
		RequestInterface $request,
		WriterInterface $configWriter
	) {
		$this->request 		= $request;
		$this->configWriter = $configWriter;
		$this->directory 	= $fileSystem->getDirectoryWrite(DirectoryList::STATIC_VIEW);
	}
	public function execute(EventObserver $observer)
	{
		//$observer->getWebsite();
		//$observer->getStore(); 

		$cache = '_cache' . DIRECTORY_SEPARATOR . 'merged' . DIRECTORY_SEPARATOR . 'stores';
		$this->directory->delete($cache);

		$alothemesParams = $this->request->getParam('groups');
		foreach (['category', 'product'] as $type) {
			if(isset($alothemesParams[$type . '_page']['fields']['page_layout']['value'])){
				$pageLayout = $alothemesParams[$type . '_page']['fields']['page_layout']['value'];
				$this->configWriter->save('web/default_layouts/default_' . $type . '_layout', $pageLayout);
			}
		}

		return $this;
	}
}