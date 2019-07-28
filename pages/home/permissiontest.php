<?
    /**
     * You can use doc comments in reflection. 
     * See more info https://php.net/manual/en/reflectionclass.getdoccomment.php
     * 
     * @role("admin")
     */

class permissiontest extends Page
{
    public function render($p)
    {
        echo 'Howdy friend!';
        //$this->renderView($p);
    }
}

?>