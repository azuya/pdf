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
     * Renders the PDF.
     *
     * @return  PDF output
     */
    public function render()
    {        
        return $this->_run_vendor('S');
    }

    /**
     * Save the PDF.
     *
     * @return  empty string
     */
    public function save($file)
    {
        return $this->_run_vendor('F', $file);
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

    /**
     * Magic method, returns the output of [PDF::render].
     *
     * @return  string
     * @uses    PDF::render
     */
    public function __toString()
    {
        try
        {
            return $this->render();
        } catch (Exception $e)
        {
            $error_response = Kohana_Exception::_handler($e);

            return $error_response->body();
        }
    }

    protected function _setup_vendor()
    {
        include_once Kohana::find_file('vendor/tcpdf', 'tcpdf');
        $this->_pdf = new TCPDF($this->_config['page']['orientation'], $this->_config['page']['unit'], $this->_config['page']['format'], $this->_config['options']['unicode'], $this->_config['options']['encoding'], $this->_config['options']['diskcache'], $this->_config['options']['pdfa']);

        $this->_pdf->SetCreator($this->_config['document']['creator']);
        $this->_pdf->SetAuthor($this->_config['document']['author']);
        $this->_pdf->SetTitle($this->_config['document']['title']);
        $this->_pdf->SetSubject($this->_config['document']['subject']);
        $this->_pdf->SetKeywords($this->_config['document']['keywords']);

        $this->_pdf->SetHeaderData($this->_config['document']['header_logo'], $this->_config['document']['header_logo_width'], $this->_config['document']['header_title'], $this->_config['document']['header_string']);

        $this->_pdf->setHeaderFont(Array($this->_config['fonts']['main']['name'], '', $this->_config['fonts']['main']['size']));
        $this->_pdf->setFooterFont(Array($this->_config['fonts']['data']['name'], '', $this->_config['fonts']['data']['size']));

        $this->_pdf->SetDefaultMonospacedFont($this->_config['fonts']['monospaced']['name']);

        $this->_pdf->SetMargins($this->_config['page']['margins']['left'], $this->_config['page']['margins']['top'], $this->_config['page']['margins']['right']);
        $this->_pdf->SetHeaderMargin($this->_config['page']['margins']['header']);
        $this->_pdf->SetFooterMargin($this->_config['page']['margins']['footer']);

        $this->_pdf->SetAutoPageBreak(TRUE, $this->_config['page']['margins']['bottom']);

        $this->_pdf->setImageScale($this->_config['scaling']['image_scale_ratio']);

        $this->_pdf->SetFont($this->_config['fonts']['data']['name'], '', $this->_config['fonts']['data']['size'], '', false);
        $this->_pdf->AddPage();
        $this->_pdf->writeHTML($this->_view, true, false, true, false, '');

        return $this;
    }
    
    protected function _run_vendor($option = 'S', $file = 'doc.pdf')
    {
        $this->_setup_vendor();
        return $this->_pdf->Output($file, $option);        
    }

}

// End PDF
