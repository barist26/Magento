<?php
/**
 * Pdf_Lookbook Adminhtml Save Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;
use Pdf\Lookbook\Model\LookbookFactory;
use Pdf\Lookbook\Model\Lookbook\ImageUploader;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Pdf_Lookbook::lookbook_manage';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LookbookFactory
     */
    protected $lookbookFactory;

    /**
     * @var LookbookRepositoryInterface
     */
    protected $lookbookRepository;

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param LookbookFactory $lookbookFactory
     * @param LookbookRepositoryInterface $lookbookRepository
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        LookbookFactory $lookbookFactory,
        LookbookRepositoryInterface $lookbookRepository,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->lookbookFactory = $lookbookFactory;
        $this->lookbookRepository = $lookbookRepository;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = 1;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            // Handle PDF file upload
            if (isset($data['pdf_file']) && is_array($data['pdf_file'])) {
                if (!empty($data['pdf_file']['delete'])) {
                    $data['pdf_file'] = null;
                } else {
                    if (isset($data['pdf_file'][0]['name']) && isset($data['pdf_file'][0]['tmp_name'])) {
                        $data['pdf_file'] = $data['pdf_file'][0]['name'];
                        $this->imageUploader->moveFileFromTmp($data['pdf_file'], 'pdf');
                    } else {
                        if (isset($data['pdf_file'][0]['name'])) {
                            $data['pdf_file'] = $data['pdf_file'][0]['name'];
                        }
                    }
                }
            }

            // Handle cover image upload
            if (isset($data['cover_image']) && is_array($data['cover_image'])) {
                if (!empty($data['cover_image']['delete'])) {
                    $data['cover_image'] = null;
                } else {
                    if (isset($data['cover_image'][0]['name']) && isset($data['cover_image'][0]['tmp_name'])) {
                        $data['cover_image'] = $data['cover_image'][0]['name'];
                        $this->imageUploader->moveFileFromTmp($data['cover_image'], 'image');
                    } else {
                        if (isset($data['cover_image'][0]['name'])) {
                            $data['cover_image'] = $data['cover_image'][0]['name'];
                        }
                    }
                }
            }

            // Handle store view selection
            if (isset($data['store_id']) && is_array($data['store_id'])) {
                $data['store_id'] = implode(',', $data['store_id']);
            }

            /** @var \Pdf\Lookbook\Model\Lookbook $model */
            $model = $this->lookbookFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $model = $this->lookbookRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This lookbook no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->lookbookRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the lookbook.'));
                $this->dataPersistor->clear('pdf_lookbook');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the lookbook.'));
            }

            $this->dataPersistor->set('pdf_lookbook', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
