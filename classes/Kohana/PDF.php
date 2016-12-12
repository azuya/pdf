<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PDF document generator.
 *
 * @package    Kohana/PDF
 * @category   Processing
 * @author     Dariusz Rorat
 * @copyright  (c) 2016 Dariusz Rorat
 * @license    BSD
 */
class Kohana_PDF
{

    // Merged configuration settings
    protected $_config = array(
    );
    protected $_pdf;
    protected $_view;

    /**
     * Creates a new PDF object.
     *
     * @param   view, array  configuration
     * @return  PDF
     */
    public static function factory($view, array $config = array())
    {
        return new PDF($view, $config);
    }

    /**
     * Creates a new PDF object.
     *
     * @param   vier, array  configuration
     * @return  void
     */
    public function __construct($view, array $config = array())
    {
        $this->_view = $view;
        // Overwrite system defaults with application defaults
        $this->_config = $this->config_group() + $this->_config;

        // PDF setup
        $this->setup($config);

        $this->_setup_vendor();
    }

    /**
     * Retrieves a PDF config group from the config file. One config group can
     * refer to another as its parent, which will be recursively loaded.
     *
     * @param   string  PDF config group; "default" if none given
     * @return  array   config settings
     */
    public function config_group($group = 'default')
    {
        // Load the PDF config file
        $config_file = Kohana::$config->load('pdf');

        // Initialize the $config array
        $config['group'] = (string) $group;

        // Recursively load requested config groups
        while (isset($config['group']) AND isset($config_file->$config['group']))
        {
            // Temporarily store config group name
            $group = $config['group'];
            unset($config['group']);

            // Add config group values, not overwriting existing keys
            $config += $config_file->$group;
        }

        // Get rid of possible stray config group names
        unset($config['group']);

        // Return the merged config group settings
        return $config;
    }

    /**
     * Loads configuration settings into the object and (re)calculates PDF if needed.
     * Allows you to update config settings after a PDF object has been constructed.
     *
     * @param   array   configuration
     * @return  object  PDF
     */
    public function setup(array $config = array())
    {
        if (isset($config['group']))
        {
            // Recursively load requested config groups
            $config += $this->config_group($config['group']);
        }

        // Overwrite the current config settings
        $this->_config = $config + $this->_config;

        // Chainable method
        return $this;
    }

    /**
     * Downloads the PDF.
     *
     */
    public function download($file)
    {
        $this->_pdf->loadHtml($this->_view);
        $this->_pdf->render();
        $this->_pdf->stream($file);
    }

    /**
     * Save the PDF.
     *
     * @return  PDF as string
     */
    public function save($file)
    {
        $this->_pdf->loadHtml($this->_view);
        $this->_pdf->render();
        $output = $this->_pdf->output();
        file_put_contents($file, $output);
    }

    /**
     * Streams the PDF.
     *
     * @return  PDF output
     */
    public function stream()
    {
        $this->_pdf->loadHtml($this->_view);
        $this->_pdf->render();
        $this->_pdf->stream('document.pdf', array('Attachment' => 0));
    }

    /**
     * Returns a PDF property.
     *
     * @param   string  property name
     * @return  mixed   PDF property; NULL if not found
     */
    public function __get($key)
    {
        return isset($this->_config[$key]) ? $this->_config[$key] : NULL;
    }

    /**
     * Updates a single config setting, and recalculates PDF if needed.
     *
     * @param   string  config key
     * @param   mixed   config value
     * @return  void
     */
    public function __set($key, $value)
    {
        $this->setup(array($key => $value));
    }

    protected function _setup_vendor()
    {
        Kohana::load(Kohana::find_file('vendor', 'dompdf/autoload.inc'));
        $this->_pdf = new Dompdf\Dompdf();
        $this->_pdf->setPaper(Arr::path($this->_config, 'page.format'), Arr::path($this->_config, 'page.orientation'));
        return $this;
    }

}

// End PDF
