<?php

             /// MONITORR ///
    // https://github.com/monitorr/Monitorr


    /**
     * This script uses CURL to check if given HOST is serving a webpage. 
     * If CURL fails, use a PING (pfsockopen) function to check if anything is listening at given URL

    * PHP/cURL function to check a web site status. If HTTP status is between 200 and 400,
    * Generally all successes are in this range, the website is reachable.
    * 
    * URL MUST contain a PORT after HOST
    * URL CAN include any protocol or sub-path
    *
    *
    * @param string $url URL that must be checked
    */

    function url_to_domain($url) {

        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);

            if (!$host)

                echo "<b> ////// Invalid URL in config.php : $url ////// </b> <br />\n";

                // $host = $url;

            if (substr($host, 0, 4) == "www.")
                $host = substr($host, 4);
            if (strlen($host) > 50)
                $host = substr($host, 0, 47) . '...';

            return $host . ":" . $port . $path;

    } 

    function urlExists($url) {
        
        global $t;
        global $k;

        $handle = curl_init($url);

        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($handle, CURLOPT_FORBID_REUSE, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_TCP_FASTOPEN, true);
        curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 10.0)");
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        //curl_setopt($handle, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $curlCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

            if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401 || $httpCode == 403 || $httpCode == 405 || $curlCode == 8 || $curlCode == 67 || $curlCode == 530 || $curlCode == 60 ) {

                //echo ONLINE;

                echo '<div class="col-lg-4">';
                    echo '<a class="servicetile" href="'. $k['link'] .'" target="_blank" style="display: block">';
                
                        echo '<div id="serviceimg">';
                            echo '<div><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:5.5rem" alt=' . strtolower($t) . '></div>';
                        echo '</div>';
                        
                        echo '<div id="servicetitle">';
                           // echo '<div class="servicetext">';
                                echo '<div>'. ucfirst($t) .'</div>';
                            // echo '</div>';
                        echo '</div>'; 

                        echo '<div class="btnonline">Online</div>';
                        
                    echo '</a>'; 
                echo '</div>';

                curl_close($handle);

            } 


            else {

                $fp = fsockopen(url_to_domain($url), $timeout = 5);

                    stream_context_set_default( [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ]);

                    if (!$fp) {

                        //echo OFFLINE;

                        echo '<div class="col-lg-4">';
                            echo '<div class="servicetileoffline">';

                                echo '<div id="serviceimg">';
                                    echo '<div class="offline"><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:5.5rem" alt=' . strtolower($t) . '></div>';
                                echo '</div>';
                                
                                echo '<a id="servicetitleoffline" href="'. $k['link'] .'" target="_blank" style="display: block">';
                                    echo '<div>'. ucfirst($t) .'</div>';
                                echo '</a>';
                                
                                echo '<div class="btnoffline">Offline</div>';

                            echo '</div>';

                        echo '</div>';
                        
                    } 
                    
                    else {
                            
                        //echo UNRESPONSIVE;

                        echo '<div class="col-lg-4">';
                            echo '<a class="servicetile" href="'. $k['link'] .'" target="_blank" style="display: block">';
                        
                                echo '<div id="serviceimg">';
                                    echo '<div><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:5.5rem" alt=' . strtolower($t) . '></div>';
                                echo '</div>';
                                
                                echo '<div id="servicetitle">';
                                    //echo '<div class="servicetext">';
                                        echo '<div>'. ucfirst($t) .'</div>';
                                    //echo '</div>';
                                echo '</div>'; 

                                echo '<div class="btunknown">Unresponsive</div>';
                                
                            echo '</a>'; 
                        echo '</div>'; 

                        fclose($fp);

                    }
                
            }
            
    };

?>
