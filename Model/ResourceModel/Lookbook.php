<?php
/**
 * Pdf_Lookbook Lookbook ResourceModel
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Lookbook extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pdf_lookbook', 'entity_id');
    }
}
