<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{

    var $obj;
    var $layout;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->theme = 'tealcrm';//environment_is('testing') ? 'mvp-ready' : 'origin';
        $this->layout = '/layouts/default';
    }

    function setTheme($theme)
    {
      $this->theme = $theme;
    }

    function getTheme($base=true)
    {
       return '';
    }

    function setLayout($layout)
    {
      $this->layout = $layout;
    }    

    function getLayout(){
        return $this->layout;
    }

    function view($view, $data=null, $return=false)
    {	    
	    
	    //var_dump($view);exit();
        $loadedData = array() + $data;
        xdebug_break();
        $loadedData['content_for_layout'] = $this->ci->load->view($this->getTheme() . $view, $data, true);

        if($return)
        {
            return $this->ci->load->view($this->getTheme() . $this->getLayout(), $loadedData, true);           
        }
        else
        {
            $this->ci->load->view($this->getTheme() . $this->getLayout(), $loadedData, false);
        }
    }
}


/* End of file Layout.php */
/* Location: ./application/libraries/Layout.php */