<?php

/**
 * Created by PhpStorm.
 * User: Mahedi Azad
 * Date: 19-Mar-16
 * Time: 11:53 AM
 */
class MY_Loader extends CI_Loader
{
    public function render($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
            return $this->view($template_name, $vars, $return);
        else:
            $this->view($template_name, $vars);
        endif;
    }

    public function render_default($template_name, $vars = array(), $return = FALSE)
    {
        $header = 'header';
        $footer = 'footer';
        if($return):
            $content  = $this->view($header, $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view($footer, $vars, $return);

            return $content;
        else:
            $this->view($header, $vars);
            $this->view($template_name, $vars);
            $this->view($footer, $vars);
        endif;
    }

    public function render_content($template_name, $vars = array(), $return = FALSE)
    {
        $header = 'header';
        $footer = 'footer';
        if($return):
            $content  = $this->view($header, $vars, $return);
            $content .= $this->view($template_name, $vars, $return);
            $content .= $this->view($footer, $vars, $return);

            return $content;
        else:
            $this->view($header, $vars);
            $this->view($template_name, $vars);
            $this->view($footer, $vars);
        endif;
    }

}