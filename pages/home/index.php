<?php
class index extends Page
{
    public function render($p)
    {
        include('index.view.php');
        //or  just
        /*
        echo "<pre>Params are: ";
        print_r($p);
        echo "</pre>";
        */
        // TODO wrap view.php 
    }
}

