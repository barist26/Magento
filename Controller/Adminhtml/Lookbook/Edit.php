<?php
/**
 * Pdf_Lookbook Adminhtml Edit Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;
use Pdf\Lookbook\Model\LookbookFactory;

class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Pdf_Lookbook::lookbook_manage';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LookbookFactory
     */
    protected $lookbookFactory;

    /**
     * @var LookbookRepositoryInterface
     */
    protected $lookbookRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param LookbookFactory $lookbookFactory
     * @param LookbookRepositoryInterface $lookbookRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        LookbookFactory $lookbookFactory,
        LookbookRepositoryInterface $lookbookRepository
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->lookbookFactory = $lookbookFactory;
        $this->lookbookRepository = $lookbookRepository;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->lookbookFactory->create();

        if ($id) {
            try {
                $model = $this->lookbookRepository->getById($id);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This lookbook no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('pdf_lookbook', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Pdf_Lookbook::lookbook');
        $resultPage->addBreadcrumb(__('Lookbook PDFs'), __('Lookbook PDFs'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Lookbook') : __('New Lookbook'),
            $id ? __('Edit Lookbook') : __('New Lookbook')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Lookbook PDFs'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Lookbook'));

        return $resultPage;
    }
}
