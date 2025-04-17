<?php
/**
 * Pdf_Lookbook GenericButton Block
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Block\Adminhtml\Lookbook\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

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
        $this->context = $context;
        $this->lookbookRepository = $lookbookRepository;
    }

    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId()
    {
        try {
            return $this->lookbookRepository->getById(
                $this->context->getRequest()->getParam('entity_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
