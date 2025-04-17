<?php
/**
 * Pdf_Lookbook LookbookSearchResultsInterface
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LookbookSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \Pdf\Lookbook\Api\Data\LookbookInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Pdf\Lookbook\Api\Data\LookbookInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
