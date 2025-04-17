<?php
/**
 * Pdf_Lookbook Pdf View Block
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Block\Pdf;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Pdf\Lookbook\Helper\Data as LookbookHelper;

class View extends Template
{
    /**
     * @var LookbookRepositoryInterface
     */
    protected $lookbookRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var LookbookHelper
     */
    protected $lookbookHelper;

    /**
     * @param Context $context
     * @param LookbookRepositoryInterface $lookbookRepository
     * @param RequestInterface $request
     * @param LookbookHelper $lookbookHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        LookbookRepositoryInterface $lookbookRepository,
        RequestInterface $request,
        LookbookHelper $lookbookHelper,
        array $data = []
    ) {
        $this->lookbookRepository = $lookbookRepository;
        $this->request = $request;
        $this->lookbookHelper = $lookbookHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get current lookbook
     *
     * @return \Pdf\Lookbook\Api\Data\LookbookInterface|null
     */
    public function getLookbook()
    {
        $id = $this->request->getParam('id');

        if (!$id) {
            return null;
        }

        try {
            return $this->lookbookRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get PDF URL
     *
     * @return string
     */
    public function getPdfUrl()
    {
        $lookbook = $this->getLookbook();

        if (!$lookbook) {
            return '';
        }

        return $this->lookbookHelper->getPdfUrl($lookbook->getPdfFile());
    }

    /**
     * Get lookbook title
     *
     * @return string
     */
    public function getLookbookTitle()
    {
        $lookbook = $this->getLookbook();

        if (!$lookbook) {
            return '';
        }

        return $lookbook->getTitle();
    }

    /**
     * Get lookbook description
     *
     * @return string
     */
    public function getLookbookDescription()
    {
        $lookbook = $this->getLookbook();

        if (!$lookbook) {
            return '';
        }

        return $lookbook->getDescription();
    }

    /**
     * Get back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('lookbook');
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
}
