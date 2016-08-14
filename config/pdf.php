<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    // Application defaults
    'default' => array(
        'document' => array(
            'creator' => 'Kohana',
            'author' => 'Kohana',
            'title' => 'Kohana',
            'subject' => 'Kohana',
            'keywords' => 'TCPDF, PDF, Kohana',
            'header_title' => 'PDF document',
            'header_string' => 'Kohana',
            'header_logo' => '',
            'header_logo_width' => 0,
        ),
        'page' => array(
            'format' => 'A4',
            'orientation' => 'P', // P - portrait, L - landscape
            'unit' => 'mm',
            'margins' => array(
                'header' => 5,
                'footer' => 10,
                'top' => 27,
                'bottom' => 25,
                'left' => 15,
                'right' => 15,
            ),
        ),
        'fonts' => array(
            'main' => array(
                'name' => 'dejavusans',
                'size' => 10,
            ),
            'data' => array(
                'name' => 'dejavusans',
                'size' => 8,
            ),
            'monospaced' => array(
                'name' => 'courier',
                'size' => 10,
            ),
        ),
        'scaling' => array(
            'image_scale_ratio' => 1.25,
            'head_maginification' => 1.1,
            'cell_height_ratio' => 1.25,
            'title_magnification' => 1.3,
            'small_ratio' => 2 / 3,
        ),
        'options' => array(
            'thai_topchars' => true,
            'calls_in_html' => false,
            'throw_exception_error' => false,
            'timezone' => 'UTC',
            'unicode' => true,
            'encoding' => 'UTF-8',
            'diskcache' => false,
            'pdfa' => false,
        ),
    ),
);
