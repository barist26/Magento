<?php
/**
 * Pdf_Lookbook DataPersistor Plugin
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Plugin;

use Magento\Framework\App\Request\DataPersistorInterface;

class DataPersistor
{
    /**
     * Around get
     *
     * @param DataPersistorInterface $subject
     * @param callable $proceed
     * @param string $key
     * @return mixed
     */
    public function aroundGet(
        DataPersistorInterface $subject,
        callable $proceed,
        $key
    ) {
        $result = $proceed($key);
        if ($key === 'pdf_lookbook' && is_array($result)) {
            if (isset($result['pdf_file']) && is_array($result['pdf_file'])) {
                if (isset($result['pdf_file'][0]['name'])) {
                    $result['pdf_file'] = $result['pdf_file'][0]['name'];
                }
            }
            if (isset($result['cover_image']) && is_array($result['cover_image'])) {
                if (isset($result['cover_image'][0]['name'])) {
                    $result['cover_image'] = $result['cover_image'][0]['name'];
                }
            }
        }
        return $result;
    }
}
