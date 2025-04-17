<?php
/**
 * Pdf_Lookbook LookbookRepository
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Pdf\Lookbook\Api\Data\LookbookInterface;
use Pdf\Lookbook\Api\Data\LookbookSearchResultsInterfaceFactory;
use Pdf\Lookbook\Api\LookbookRepositoryInterface;
use Pdf\Lookbook\Model\ResourceModel\Lookbook as ResourceLookbook;
use Pdf\Lookbook\Model\ResourceModel\Lookbook\CollectionFactory as LookbookCollectionFactory;

class LookbookRepository implements LookbookRepositoryInterface
{
    /**
     * @var ResourceLookbook
     */
    protected $resource;

    /**
     * @var LookbookFactory
     */
    protected $lookbookFactory;

    /**
     * @var LookbookCollectionFactory
     */
    protected $lookbookCollectionFactory;

    /**
     * @var LookbookSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @param ResourceLookbook $resource
     * @param LookbookFactory $lookbookFactory
     * @param LookbookCollectionFactory $lookbookCollectionFactory
     * @param LookbookSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceLookbook $resource,
        LookbookFactory $lookbookFactory,
        LookbookCollectionFactory $lookbookCollectionFactory,
        LookbookSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->lookbookFactory = $lookbookFactory;
        $this->lookbookCollectionFactory = $lookbookCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save lookbook
     *
     * @param LookbookInterface $lookbook
     * @return LookbookInterface
     * @throws CouldNotSaveException
     */
    public function save(LookbookInterface $lookbook)
    {
        try {
            $this->resource->save($lookbook);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $lookbook;
    }

    /**
     * Get by id
     *
     * @param int $id
     * @return LookbookInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $lookbook = $this->lookbookFactory->create();
        $this->resource->load($lookbook, $id);
        if (!$lookbook->getId()) {
            throw new NoSuchEntityException(__('The lookbook with the "%1" ID doesn\'t exist.', $id));
        }
        return $lookbook;
    }

    /**
     * Delete
     *
     * @param LookbookInterface $lookbook
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(LookbookInterface $lookbook)
    {
        try {
            $this->resource->delete($lookbook);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Get list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Pdf\Lookbook\Api\Data\LookbookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->lookbookCollectionFactory->create();
        
        $this->collectionProcessor->process($searchCriteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
