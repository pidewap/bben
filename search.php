<?php
   
   header('Content-type: application/json');
   // Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );

        $ch = curl_init();  // Initialising cURL
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }
?>
  <?php
    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }
?>
<?php
    $scraped_page = curl("https://www.youtube.com/results?search_query=bts");
    $results_page = scrape_between($scraped_page, "var ytInitialData =",";</script>"); // Scraping out only the middle section of the results page that contains our results
   //echo $results_page;
       $json = json_decode($results_page,true);
       echo '<pre>';
//print_r($json);


$listing = $json['contents']['twoColumnSearchResultsRenderer']['primaryContents']['sectionListRenderer']['contents'][1]['itemSectionRenderer']['contents'];

      $k = 0;
        $data = [];

      foreach ($listing as $dataz) {

            @$data[$k]['id'] .= $dataz['videoRenderer']['videoId'];
            @$data[$k]['title'] .= $dataz['videoRenderer']['title']['runs'][0]['text'];
            @$data[$k]['duration'] .= $dataz['videoRenderer']['lengthText']['accessibility']['accessibilityData']['label'];
            @$data[$k]['channel'] .= $dataz['videoRenderer']['shortBylineText']['runs'][0]['text'];
            @$data[$k]['view'] .= $dataz['videoRenderer']['viewCountText']['simpleText'];
            $k++;
            }

 echo '{ "status" : "success", "items" :' . json_encode($data, JSON_UNESCAPED_UNICODE) . '}';


?>