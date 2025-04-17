<?php
/**
 * Pdf_Lookbook Helper Data
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Pdf\Lookbook\Model\Config\Source\DisplayMode;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get media URL
     *
     * @param string $path
     * @return string
     */
    public function getMediaUrl($path)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
    }

    /**
     * Get PDF URL
     *
     * @param string $pdfFile
     * @return string
     */
    public function getPdfUrl($pdfFile)
    {
        if (!$pdfFile) {
            return '';
        }
        return $this->getMediaUrl('lookbook/' . $pdfFile);
    }

    /**
     * Get cover image URL
     *
     * @param string $coverImage
     * @return string
     */
    public function getCoverImageUrl($coverImage)
    {
        if (!$coverImage) {
            return '';
        }
        return $this->getMediaUrl('lookbook/covers/' . $coverImage);
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            'pdf_lookbook/general/enabled',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display mode
     *
     * @return string
     */
    public function getDisplayMode()
    {
        return $this->scopeConfig->getValue(
            'pdf_lookbook/general/display_mode',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if display mode is embedded
     *
     * @return bool
     */
    public function isEmbeddedMode()
    {
        return $this->getDisplayMode() === DisplayMode::MODE_EMBEDDED;
    }

    /**
     * Check if display mode is flipbook
     *
     * @return bool
     */
    public function isFlipbookMode()
    {
        return $this->getDisplayMode() === DisplayMode::MODE_FLIPBOOK;
    }
}
