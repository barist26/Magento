<?php
/**
 * Pdf_Lookbook Adminhtml Index Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Pdf_Lookbook::lookbook_manage';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Pdf_Lookbook::lookbook');
        $resultPage->addBreadcrumb(__('Lookbook PDFs'), __('Lookbook PDFs'));
        $resultPage->addBreadcrumb(__('Manage Lookbooks'), __('Manage Lookbooks'));
        $resultPage->getConfig()->getTitle()->prepend(__('Lookbook PDFs'));

        return $resultPage;
    }
}
