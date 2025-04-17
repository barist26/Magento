<?php
/**
 * Pdf_Lookbook Frontend Block
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Pdf\Lookbook\Model\ResourceModel\Lookbook\CollectionFactory;
use Pdf\Lookbook\Helper\Data as LookbookHelper;

class Lookbook extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $lookbookCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LookbookHelper
     */
    protected $lookbookHelper;

    /**
     * @var int
     */
    protected $itemsPerPage;

    /**
     * @param Context $context
     * @param CollectionFactory $lookbookCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param LookbookHelper $lookbookHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $lookbookCollectionFactory,
        StoreManagerInterface $storeManager,
        LookbookHelper $lookbookHelper,
        array $data = []
    ) {
        $this->lookbookCollectionFactory = $lookbookCollectionFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->lookbookHelper = $lookbookHelper;
        $this->itemsPerPage = $this->scopeConfig->getValue(
            'pdf_lookbook/general/items_per_page',
            ScopeInterface::SCOPE_STORE
        ) ?: 6;
        parent::__construct($context, $data);
    }

    /**
     * Get lookbook collection
     *
     * @return \Pdf\Lookbook\Model\ResourceModel\Lookbook\Collection
     */
    public function getLookbookCollection()
    {
        $page = $this->getRequest()->getParam('p') ? $this->getRequest()->getParam('p') : 1;
        $pageSize = $this->itemsPerPage;

        $collection = $this->lookbookCollectionFactory->create();
        $collection->addFieldToFilter('is_active', 1);

        // Filter by current store
        $storeId = $this->storeManager->getStore()->getId();
        $collection->addFieldToFilter(
            ['store_id', 'store_id'],
            [
                ['finset' => $storeId],
                ['finset' => 0]
            ]
        );

        $collection->setOrder('sort_order', 'ASC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection;
    }

    /**
     * Get media URL
     *
     * @param string $path
     * @return string
     */
    public function getMediaUrl($path)
    {
        return $this->lookbookHelper->getMediaUrl($path);
    }

    /**
     * Get PDF URL
     *
     * @param string $pdfFile
     * @return string
     */
    public function getPdfUrl($pdfFile)
    {
        return $this->lookbookHelper->getPdfUrl($pdfFile);
    }

    /**
     * Get cover image URL
     *
     * @param string $coverImage
     * @return string
     */
    public function getCoverImageUrl($coverImage)
    {
        return $this->lookbookHelper->getCoverImageUrl($coverImage);
    }

    /**
     * Get PDF.js viewer URL
     *
     * @param string $pdfFile
     * @return string
     */
    public function getPdfJsViewerUrl($pdfFile)
    {
        $pdfUrl = $this->getPdfUrl($pdfFile);
        $viewFileUrl = $this->getViewFileUrl('Pdf_Lookbook/pdfjs/web/viewer.html');
        return $viewFileUrl . '?file=' . urlencode($pdfUrl);
    }

    /**
     * Check if display mode is embedded
     *
     * @return bool
     */
    public function isEmbeddedMode()
    {
        return $this->lookbookHelper->isEmbeddedMode();
    }

    /**
     * Check if display mode is flipbook
     *
     * @return bool
     */
    public function isFlipbookMode()
    {
        return $this->lookbookHelper->isFlipbookMode();
    }

    /**
     * Get pagination HTML
     *
     * @return string
     */
    public function getPaginationHtml()
    {
        $collection = $this->getLookbookCollection();
        $pager = $this->getChildBlock('lookbook_list_pager');

        if ($pager) {
            $pager->setCollection($collection);
            return $pager->toHtml();
        }

        return '';
    }
}
