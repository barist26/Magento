<?php
/**
 * Pdf_Lookbook Image Upload Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook\Image;

use Pdf\Lookbook\Controller\Adminhtml\Lookbook\FileUploader;

class Upload extends FileUploader
{
    /**
     * Get file ID
     *
     * @return string
     */
    protected function getFileId()
    {
        return 'cover_image';
    }

    /**
     * Get file type
     *
     * @return string
     */
    protected function getFileType()
    {
        return 'image';
    }
}