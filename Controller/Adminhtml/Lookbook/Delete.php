<?php
/**
 * Pdf_Lookbook Adminhtml Delete Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Pdf_Lookbook::lookbook_manage';

    /**
     * @var LookbookRepositoryInterface
     */
    protected $lookbookRepository;

    /**
     * @param Context $context
     * @param LookbookRepositoryInterface $lookbookRepository
     */
    public function __construct(
        Context $context,
        LookbookRepositoryInterface $lookbookRepository
    ) {
        $this->lookbookRepository = $lookbookRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                $this->lookbookRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the lookbook.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a lookbook to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
