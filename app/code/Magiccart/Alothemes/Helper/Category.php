<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magiccart\Alothemes\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;


/**
 * Alothemes category helper
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Category extends AbstractHelper
{

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CategoryRepositoryInterface $categoryRepository
    ) {

        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    public function getCategory($categoryId)
    {
        return $this->categoryRepository->get($categoryId);
    }
    
}
