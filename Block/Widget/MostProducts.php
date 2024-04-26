<?php

/**
 *
 * @category  Custom Development
 * @email     contactus@learningmagento.com
 * @author    Learning Magento
 * @website   learningmagento.com
 * @Date      19-04-2024
 */

namespace Learningmagento\Customwidget\Block\Widget;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class MostProducts extends \Magento\Catalog\Block\Product\ListProduct implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'Learningmagento_Customwidget::widget/most_products.phtml';

    protected $productReport;

    protected $productCollection;

    protected $bestSellersCollectionFactory;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $productReport,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $bestSellersCollectionFactory,
        array $data = [],
        ?OutputHelper $outputHelper = null
    )
    {
        $this->productReport = $productReport;
        $this->productCollection = $productCollection;
        $this->bestSellersCollectionFactory = $bestSellersCollectionFactory;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data, $outputHelper);
    }

    public function getLoadedProductCollection() {
        $listType = $this->getData('list_type');
        if($listType == "best_sell") {
            return $this->getBestSelling();
        }
        else if($listType == "most_viewed") {
            return $this->getMostViewed();
        }
        else if($listType == "recently_added") {
            return  $this->getRecentlyAdded();
        }
    }


    protected function getBestSelling()
    {
        $bestSellers = $this->bestSellersCollectionFactory->create()
            ->setPeriod('year');
        $productIds = [];
        foreach ($bestSellers as $product) {
            $productIds[] = $product->getProductId();
        }
        $collection = $this->productCollection->create()->addIdFilter($productIds);
        $collection->addAttributeToSelect("*")
            ->setPageSize($this->getLimit())
            ->addStoreFilter($this->getStoreId());
        return $collection;
    }

    protected function getMostViewed()
    {
        $mostviewed =  $this->productReport->create()
            ->addAttributeToSelect('*')
            ->addViewsCount()
            ->setStoreId($this->getStoreId())
            ->addStoreFilter($this->getStoreId());

        $productIds = [];
        foreach ($mostviewed as $product) {
            $productIds[] = $product->getProductId();
        }
        $collection = $this->productCollection->create()->addIdFilter($productIds);
        $collection->addAttributeToSelect("*")
            ->setPageSize($this->getLimit())
            ->addStoreFilter($this->getStoreId());
        return $collection;
    }

    protected function getRecentlyAdded()
    {
        return $this->productCollection->create()
            ->addAttributeToSelect("*")
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getLimit())
            ->addAttributeToSort('entity_id', 'desc');
    }

    protected function getLimit()
    {
        return (int) $this->getData("number_of_products");
    }

    protected function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

}
