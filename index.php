<?
define('__root', __DIR__);
define('__pages', __root . '/pages');

require_once __root . '/app/app.php';
require_once __root . '/app/lib.php';

class handler_test implements IAppHandler
{

    public function OnAppCreated($app)
    {
        //Wanna change the route path like $app->path
    }
    public function RenderHeader()
    {
        $app_name = "MVP App";
        require_once __pages . "/_layout/header.php";
    }
    public function RenderFooter()
    {
        $app_name = "MVP App";
        require_once  __pages . "/_layout/footer.php";
    }
    public function OnPageRendering($page)
    {
        $r = new ReflectionClass(get_class($page));
        $s = $r->getDocComment();
        if (!str::isNullOrEmpty(trim($s))) {
            // tags::pre($s);
            /**
             * @role("filter")
             */
            $matches = array();
            //@role("filter") https://regex101.com/
            preg_match('/@role\(\"(.*)\"\)/', $s, $matches);
            if (!arrays::isNullOrEmpty($matches)) {
            // tags::pre($matches);//Array ( [0] => @role("filter") [1] => filter ) 
            // tags::pre($matches[0]);//@role("filter")        
            // tags::pre($matches[1]);//filter
                $filter = $matches[1];
                tags::h("Check Permission for:".$filter);
                if (!userHasRights($filter)) {
                    die('You do not have permission on this page! Process stopped!');
                }
            }
    
        }
        
    }
}

function userHasRights($right) {
    return false;
}

$app = new app(new handler_test());
$app->run();
