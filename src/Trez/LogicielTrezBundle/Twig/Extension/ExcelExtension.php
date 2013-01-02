<?php

namespace Trez\LogicielTrezBundle\Twig\Extension;

/**
 * Format numbers in a Excel-like fashion (French locale):
 * 2 decimals, ' ' separator for thousands, ',' for decimals
 * and '-' in place of '0,00
 */
class ExcelExtension extends \Twig_Extension {
    /**
     * Attaches the innervars filter to the Twig Environment.
     *
     * @return array
     */
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('excel_format', function ($number) {
                if (is_numeric($number) === false && empty($number) === false) {
                    throw new \Exception("Non-numeric value '$number' passed to Twig ExcelExtension");
                }

                if ($number == 0) { // not strict!
                    return '-';
                }

                return number_format($number, '2', ',', ' ');
            })
        );
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName( ) {
        return 'excel_format';
    }
}