<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    // Application defaults
    'default' => array(
        'page' => array(
            'format' => 'A4',
            'orientation' => 'portrait',
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
    ),
);
