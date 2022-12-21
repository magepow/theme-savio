<?php

namespace Magiccart\Alothemes\Helper;

class Asset extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepository;

    public function __construct(\Magento\Framework\View\Asset\Repository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getViewFileContents($assetPath) {
        $asset = $this->assetRepository->createAsset($assetPath);

        return $asset->getContent();
    }
}