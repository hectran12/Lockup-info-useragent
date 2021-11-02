<?php


# WEBSITE : https://gs.statcounter.com/detect

# The source api is coded by: HexV

header('Content-Type: application/json');

#values to send 
$YOUR_UA = $_GET["ua"];

if(!isset($YOUR_UA))
{
  ERROR("short_data","Please send enough data!"); 
} else {
  
  #website url to get info 
  $URL_WEBSITE_CONNECT = "https://gs.statcounter.com/detect?useragent=" . urlencode($YOUR_UA);
  #Action get 
  $CONNECT_RESULT      = CONNECT($URL_WEBSITE_CONNECT);
  
  #html processing
  
  $EXP_STRONG = explode("<strong>", $CONNECT_RESULT);
  
   $MAX_FOR   = 12;
   $MIN_FOR   =  1;
   $DATA      = [];
   
   for ($HEXV = 1;$HEXV <= $MAX_FOR; $HEXV++)
   {
     $DATA_INFO = $EXP_STRONG[$HEXV];
     $EXP_CLOSE_STRONG = explode('</strong', $DATA_INFO)[0];
     $DATA[]    = $EXP_CLOSE_STRONG;
   }
   
  $RESULT_INFO = array(
    "type_result" => "success",
    "data"        => array(
      "browser_name"  => $DATA[0],
      "browser_visit" => $DATA[1],
      "os_device"     => $DATA[2],
      "vendor_device" => $DATA[3],
      "model_device"  => $DATA[4],
      "screen_width_height" => $DATA[5]."/".$DATA[6],
      "desktop_view"  => $DATA[7],
      "mobile_view"   => $DATA[8],
      "table_view"    => $DATA[9],
      "crawler/robot_view"  => $DATA[10],
      "console_view"  => $DATA[11]
    )
  );
  die(json_encode($RESULT_INFO,JSON_PRETTY_PRINT)); #print success result
}


// Function to connect to website check
function CONNECT ($URL)
{
  $CURL_CREATE = curl_init($URL);
  # Properties
  $PROT        = array(
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_CUSTOMREQUEST  => "GET"
  );
  # Action 
  curl_setopt_array($CURL_CREATE, $PROT);
  $ERROR = curl_error($CURL_CREATE); #Error message
  $EXEC  = curl_exec($CURL_CREATE); #result message
  if ($ERROR) { 
    ERROR("error","Can not connect to server"); 
  } else {
    return $EXEC;
  }
  
}
#Error result
function ERROR ($TYPE, $MESSAGE)
{
  $ERROR_RESPONE = array(
    "type_result" => $TYPE,
    "message"     => $MESSAGE
  );
  die(json_encode($ERROR_RESPONE,JSON_PRETTY_PRINT)); #print error result
}