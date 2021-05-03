<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PdfService
{
    private $container;

    /**
     * PdfService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $options
     * @return Pdf
     */
    public function newPdf($options)
    {
        $options = array_merge($this->getDefaultOptions(), $options);
        $pdf = new Pdf(null, $options);
        $binary = '\src\Bin\wkhtmltopdf.exe';

        $pdf->setBinary($this->container->getParameter('kernel.project_dir') . $binary);

        return $pdf;
    }

    /**
     * @return array|null
     */
    private function getDefaultOptions(): array
    {
        return [
            'page-size' => 'A4',
            'margin-bottom' => 10,
            'margin-top' => 10,
            'margin-left' => 10,
            'margin-right' => 10,
            //'zoom' => 0.982,
            //'footer-center' => 'Mon footer - Page [page]/[toPage]',
            //'orientation' => 'Landscape',
            'disable-smart-shrinking' => false,
            'disable-javascript' => false,
            'dpi' => intval(793 / (21 * 0.3937)) + 2 //The Qt A4 page is 793px large
        ];
    }

    /**
     * @param $html
     * @param array $option
     * @return string
     */
    public function generatePdf($html, $option = [])
    {
        $pdf = $this->newPdf($option);
        return $pdf->getOutputFromHtml($html);
    }

}
