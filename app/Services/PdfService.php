<?php

namespace App\Services;

use Mpdf\Mpdf;

class PdfService
{
    public function make(array $config = []): Mpdf
    {
        $defaults = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ];

        $mpdf = new Mpdf(array_merge($defaults, $config));
        $mpdf->SetDisplayMode('fullpage');

        return $mpdf;
    }
}
