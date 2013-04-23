<?php

namespace Trez\LogicielTrezBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;

/**
 * Format numbers in a Excel-like fashion (French locale):
 * 2 decimals, ' ' separator for thousands, ',' for decimals
 * and '-' in place of '0,00
 */
class ExcelExtension extends \Twig_Extension {
    public $currency;

    public function __construct(EntityManager $em) {
        $this->currency = $em->getRepository('TrezLogicielTrezBundle:Config')->findOneBy(
            array('cle' => 'currency')
        )->getValeur();
    }

    /**
     * Attaches the innervars filter to the Twig Environment.
     *
     * @throws \Exception
     * @return array
     */
    public function getFilters() {
        $currency = $this->currency;

        return array(
            // need Twig 1.12, before the syntax was different!
            new \Twig_SimpleFilter('excel_format', function ($number) use ($currency) {
                if (is_numeric($number) === false && empty($number) === false) {
                    throw new \Exception("Non-numeric value '$number' passed to Twig ExcelExtension");
                }

                if ($number == 0) { // not strict!
                    return '-';
                }

                return number_format($number, '2', ',', ' ') . ' ' . $currency;
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
