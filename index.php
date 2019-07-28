<?
define('__root', __DIR__);
define('__pages', __root . '/pages');

require_once __root . '/app/app.php';

class handler_test implements IAppHandler
{
    public function on_app_created($app)
    {
        //Wanna change the route path like $app->path
        
    }
    public function render_header()
    {
        $app_name = "MVP App";
        require_once __pages."/_layout/header.php";
    }
    public function render_footer()
    {
        $app_name = "MVP App";
        require_once  __pages."/_layout/footer.php";

    }

}

$app = new app(new handler_test());
$app->run();

?>

