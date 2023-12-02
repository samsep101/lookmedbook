<?php

class SiteStatisticAccessHelper {

    public static function checkAccess()
    {
        if (!Acc::isSystemAccess())
            RedirectManager::redirect('/');

        $user_ip = $_SERVER['REMOTE_ADDR'];

        if ($user_ip and $user_ip!='127.0.0.1'){

            $system_access_ip_manager = new SystemAccessIpManager();
            $system_access_ips = $system_access_ip_manager->getActiveList();
            if (count($system_access_ips))
            {
                $access = 0;
                foreach ($system_access_ips as $system_access_ip) {
                    if($user_ip==$system_access_ip->ip) {
                        $access = 1;
                        break;
                    }
                    if(strpos($system_access_ip->ip,'*') and
                      preg_match('/^'.str_replace(['.', '*'], ['\\.', '.*'], $system_access_ip->ip).'$/', $user_ip)) {
                        $access = 1;
                        break;
                    }
                }
                if (!$access) {
                    RedirectManager::redirect('/');
                }
            }
        }
    }
}