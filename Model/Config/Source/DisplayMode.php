<?php
/**
 * Pdf_Lookbook DisplayMode Source Model
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DisplayMode implements ArrayInterface
{
    /**
     * Display mode constants
     */
    const MODE_EMBEDDED = 'embedded';
    const MODE_FLIPBOOK = 'flipbook';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::MODE_EMBEDDED, 'label' => __('Embedded in Page')],
            ['value' => self::MODE_FLIPBOOK, 'label' => __('Flipbook')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::MODE_EMBEDDED => __('Embedded in Page'),
            self::MODE_FLIPBOOK => __('Flipbook')
        ];
    }
}
