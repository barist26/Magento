<?php
/**
 * Pdf_Lookbook File Upload Controller
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Controller\Adminhtml\Lookbook\File;

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
        return 'pdf_file';
    }

    /**
     * Get file type
     *
     * @return string
     */
    protected function getFileType()
    {
        return 'pdf';
    }
}