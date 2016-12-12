# Kohana PDF export module

This module is used to perform PDF generation from HTML view.

## Requirements

You must install DOMPDF as vendor

https://github.com/dompdf/dompdf

## Example:

Basic render generated pdf and stream to output:

```php
        $view = View::factory('pdf/test');
        PDF::factory($view)->stream();
```

Render cached for 24 hrs. generated pdf:

```php
        $file = Kohana::$cache_dir . DIRECTORY_SEPARATOR . 'test.pdf';
        $lifetime = 86400;

        if (!is_file($file) || ((time() - filemtime($file)) >= $lifetime))
        {
            $view = View::factory('pdf/test');
            PDF::factory($view)->save($file);
        }

        $this->response->send_file($file, NULL, array('inline' => true));
```


Save PDF:

```php
        $file = APPPATH . 'storage' . DIRECTORY_SEPARATOR . 'output.pdf';
        $view = View::factory('pdf/test');
        $pdf = PDF::factory($view)->save($file);
```

Download PDF:

```php
        $view = View::factory('pdf/test');
        $pdf = PDF::factory($view)->download('test.pdf');
```

## Config

pdf.php

```php
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

```
