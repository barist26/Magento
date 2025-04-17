<?php
/**
 * Pdf_Lookbook Widget Slider Block
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Pdf\Lookbook\Model\ResourceModel\Lookbook\CollectionFactory;
use Pdf\Lookbook\Helper\Data as LookbookHelper;
use Magento\Store\Model\StoreManagerInterface;

class Slider extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'widget/slider.phtml';

    /**
     * @var CollectionFactory
     */
    protected $lookbookCollectionFactory;

    /**
     * @var LookbookHelper
     */
    protected $lookbookHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Template\Context $context
     * @param CollectionFactory $lookbookCollectionFactory
     * @param LookbookHelper $lookbookHelper
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $lookbookCollectionFactory,
        LookbookHelper $lookbookHelper,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->lookbookCollectionFactory = $lookbookCollectionFactory;
        $this->lookbookHelper = $lookbookHelper;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Get lookbook collection
     *
     * @return \Pdf\Lookbook\Model\ResourceModel\Lookbook\Collection
     */
    public function getLookbookCollection()
    {
        $count = $this->getData('number_of_lookbooks') ? (int)$this->getData('number_of_lookbooks') : 10;

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
        $collection->setPageSize($count);

        return $collection;
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
     * Get slider title
     *
     * @return string
     */
    public function getSliderTitle()
    {
        return $this->getData('slider_title') ?: __('Catalogs');
    }

    /**
     * Get unique slider ID
     *
     * @return string
     */
    public function getSliderId()
    {
        return 'lookbook-slider-' . $this->getNameInLayout();
    }
}
