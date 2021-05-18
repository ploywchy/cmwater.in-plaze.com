<?php

namespace PHPMaker2021\inplaze;

/**
 * Class for export to PDF
 */
class ExportReportPdf
{
    // Export
    public function __invoke($page, $html)
    {
        echo $html;
        exit();
    }
}
