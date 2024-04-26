<?php

/**
 *
 * @category  Custom Development
 * @email     contactus@learningmagento.com
 * @author    Learning Magento
 * @website   learningmagento.com
 * @Date      19-04-2024
 */

namespace Learningmagento\Customwidget\Model\Config\Source;

class ProductType implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'best_sell', 'label' => __('Best Selling of Month')],
            ['value' => 'most_viewed', 'label' => __('Most viewed')],
            ['value' => 'recently_added', 'label' => __('Recently Added')]
        ];
    }
}
