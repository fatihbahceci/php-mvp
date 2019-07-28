<?
require_once 'lib.php';

if (!defined('__pages')) {
    die('the variable __pages is not defined');
}
interface IAppHandler
{
    public function OnAppCreated($app);
    public function RenderHeader();
    public function RenderFooter();
    public function OnPageRendering($page);

}

class App
{
    public $path, $page, $params;
    private $handler;

    public function __construct(IAppHandler $handler = null)
    {
        $get = $_GET;
        $url = "home/index";
        //mod_rewirte url/x/y/z => ?q=/x/y/z
        if (isset($get["q"])) {
            if (!empty($get["q"])) {
                $url = trim($get["q"], "/");
            }
            //Delete values after get
            unset($get["q"]);
        }
        //explode params
        $url = explode("/", $url);

        $endoffolders = false;
        $_path = "";
        $checkpath = __pages;
        /**   PATH CHECK   */
        while (!$endoffolders && (count($url)) > 0) {
            $checkpath .= "/" . $url[0];
            if (sys::isFolderExists($checkpath)) {
                $_path .= "/" . $url[0];
                array_shift($url); //delete first item
            } else {
                $endoffolders = true;
            }
        }
        if (!str::isNullOrEmpty($_path)) {
            $this->path = $_path;
        }
        /** EOF AREA CHECK */
        $this->page = "index";
        if (!str::isNullOrEmpty($url[0])) {
            $this->page = $url[0];
            array_shift($url); //delete first item
        }
        //asign remainder url and other get params to the params
        $this->params = array_merge($url, $get);
        if ($handler != null) {
            $this->handler = $handler;
            $handler->OnAppCreated($this);
        }
    }
    public function run()
    {
        $currentpath = __pages;
        if (!str::isNullOrEmpty($this->path)) {
            $currentpath .= "/{$this->path}";
        }
        // {already checked in constructor} if (
        //     str::isNullOrEmpty($this->path) ||
        //     !str::isNullOrEmpty($this->path) && sys::isFolderExists($currentpath .= "/{$this->path}")
        // ) {
        // is there a php file named $page
        if (file_exists($file = $currentpath . "/{$this->page}.php")) {

            //Turn on output buffering
            ob_start();
            if ($this->handler != null) {
                $this->handler->RenderHeader();
            }

            // include the file in our system
            require_once $file;
            if (class_exists($this->page)) {
                $theClass = new $this->page;
                //if (method_exists($theClass, "render")) {
                if ($theClass instanceof Page) {
                    if ($this->handler != null) {
                        $this->handler->OnPageRendering($theClass);
                    }
                        $theClass->render($this->params);


                } else {
                    //Clean (erase) the output buffer and turn off output buffering
                    ob_end_clean();
                    die("{$this->page} has not instance of Page class!");
                }
            } else {
                //Clean (erase) the output buffer and turn off output buffering
                ob_end_clean();
                die("{$this->page}.php does not have named class!");
            }
            if ($this->handler != null) {
                $this->handler->RenderFooter();
            }

            //Get current buffer contents and delete current output buffer
            echo ob_get_clean();
        } else {
            //exit("The file $file is not exists!");
            exit("The file {$this->page} is not exists!");
        }
        // } else {
        //     //exit("Directory $currentpath is not exists!");
        //     exit("Directory {$this->path} is not exists!");
        // }
    }
}

abstract class Page
{

    //public $allowRender;

    public function render($params)
    {
        die('Method not implemented!');
    }

    final public function renderView($model)
    {
        // echo __CLASS__;
        $dir = dirname((new ReflectionClass($this))->getFileName());
        $class = get_class($this);
        wrapInclude(("$dir/$class.view.php"), $model);
    }
}

function wrapInclude($path, $model)
{
    include $path;
}
