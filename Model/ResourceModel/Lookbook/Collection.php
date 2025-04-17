<?php
/**
 * Pdf_Lookbook Lookbook Collection
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model\ResourceModel\Lookbook;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'pdf_lookbook_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'lookbook_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Pdf\Lookbook\Model\Lookbook::class,
            \Pdf\Lookbook\Model\ResourceModel\Lookbook::class
        );
    }
}
