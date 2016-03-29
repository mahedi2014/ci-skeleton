<?php
/**
 * Created by Mahedi.
 * User: RDF
 * Date: 1/13/15
 * Time: 10:56 AM
 */
class UserLib {
    public $user_agent = '';
    public $ipaddress = '';
    public $browser = '';

    public function UserLib() {
        $this->user_agent =   $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Function to get the users ip address
     * @return string ip address
     */
    public function  getUserIp() {
        if ($_SERVER['HTTP_CLIENT_IP'])
            $this->ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR'])
            $this->ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED'])
            $this->ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR'])
            $this->ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED'])
            $this->ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR'])
            $this->ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $this->ipaddress = 'UNKNOWN';

        return $this->ipaddress;
    }

    /**
     * Description: Get users operation system information
     * @return string operating system
     */
    public function getUserOS() {
        $os_platform    =   "Unknown OS Platform";

        $os_array       =   array(
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $this->user_agent)) {
                $os_platform    =   $value;
            }

        }

        return $os_platform;
    }


    /**
     * Description: Get user browser information
     * @return string browser
     */
    public function getUserBrowser() {
        $this->browser        =   "Unknown Browser";

        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $this->user_agent)) {
                $this->browser    =   $value;
            }

        }

        return $this->browser;

    }


    /**
     * Description: Get unique id for each time as id
     * @return string
     */
    public static function uniqueId()
    {
        return time().rand(0,999);
    }



    /**
     * Authentication for user is valid or not
     */
    function sessionAuthenticate() {
        if(!isset($_SESSION["LoginUserID"])) {

            header("Location: ../signout.php");
            exit;
        }
        if(!isset($_SESSION["LoginUserIP"]) || ($_SESSION["LoginUserIP"] != $_SERVER["REMOTE_ADDR"])) {

            header("Location: ../signout.php");
            exit;
        }
    }

    /**
     * Users menu permission
     * @param $permission create, edit, delete, view
     * @return int 1 for allow permission and 0 for not permission
     *
     * Using: userMenuPermission('create')
     * Require: session_start(); and require_once '../authentication.php';
     */
    public function userPermission($permission){
        //echo $_POST['user'].'/'.$_SESSION["LoginUserID"].'/'.$_POST['menuId'];;

//        $menuID = $_SESSION['MENU_ID'];
       // if( $_SESSION["LoginUserID"] ===  $_POST['user']){
//            $menuID = $_POST['menuId'];

        $menuID = ($_POST['menuId']=='')?$_SESSION['MENU_ID']:$_POST['menuId'];
            $permission = strtolower($permission);

            $menuPermission = $_SESSION["LoginUserMenu"];

            switch($permission){
                case 'create': //create permission
                    return $menuPermission[$menuID][$permission];
                    break;

                case 'edit': //edit permission
                    return $menuPermission[$menuID][$permission];
                    break;

                case 'delete': //delete permission
                    return $menuPermission[$menuID][$permission];
                    break;

                case 'view': //view permission
                    return $menuPermission[$menuID][$permission];
                    break;

                case 'check': //view permission
                    return $menuPermission[$menuID][$permission];
                    break;

                default:
                    return 0;
                    break;
            }
        /*}else{
            return 0;
        }*/

    }

    /**
     * Get menu name and id on db like menu_name@123
     */
    public function getMenuLink() {
        return $_GET['pageTitle'];
    }


    /**
     * Get menu page content
     */
    public function getMenuPageContent() {
        $menu =   explode('@', $_GET['pageTitle']);

        return $menu[0];
    }

    /**
     * Get menu id on db like 123 from menu_name@123
     */
    public function getMenuId() {
        $menu =   explode('@', $_GET['pageTitle']);

        return $menu[1];
    }

    /**
     * Get data from page
     * @rules avoud @ to make data
     */
    public function getData() {
        $menu =   explode('@', $_GET['pageTitle']);

        return $menu[1];
    }

    /**
     * Get user id and id on db like menu_name@123
     */
    public static function  getUserId() {
        return $_SESSION["LoginUserID"];
    }

}

