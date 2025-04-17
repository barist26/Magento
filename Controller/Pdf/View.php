<?php
/**
 * Pdf_Lookbook Pdf View Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Pdf;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\Result\ForwardFactory;

class View extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LookbookRepositoryInterface
     */
    protected $lookbookRepository;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LookbookRepositoryInterface $lookbookRepository
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LookbookRepositoryInterface $lookbookRepository,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->lookbookRepository = $lookbookRepository;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * PDF View page
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Forward
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        
        if (!$id) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        
        try {
            $lookbook = $this->lookbookRepository->getById($id);
            
            if (!$lookbook->getIsActive()) {
                $resultForward = $this->resultForwardFactory->create();
                return $resultForward->forward('noroute');
            }
            
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($lookbook->getTitle());
            
            return $resultPage;
        } catch (NoSuchEntityException $e) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
    }
}
