<?php
/**
 * Pdf_Lookbook LookbookRepositoryInterface
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Pdf\Lookbook\Api\Data\LookbookInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface LookbookRepositoryInterface
{
    /**
     * Save lookbook
     *
     * @param LookbookInterface $lookbook
     * @return LookbookInterface
     * @throws LocalizedException
     */
    public function save(LookbookInterface $lookbook);

    /**
     * Get by id
     *
     * @param int $id
     * @return LookbookInterface
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Delete
     *
     * @param LookbookInterface $lookbook
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(LookbookInterface $lookbook);

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id);

    /**
     * Get list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Pdf\Lookbook\Api\Data\LookbookSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
