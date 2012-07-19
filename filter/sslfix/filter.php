<?php
function sslfix_filter($courseid, $text) {

  global $CFG;

  //Fix embedded youtube content...
  $text = str_replace('http://www.youtube.com/embed/', 'https://www.youtube.com/embed/', $text);

  //Reverse proxy the cheesy voki...
  $text = str_replace('http://vhss-d.oddcast.com/', $CFG->wwwroot.'/voki/', $text);
  $text = str_replace( urlencode('http://vhss-d.oddcast.com/'), urlencode($CFG->wwwroot.'/voki/'), $text);

  //Fix embed codes for Flowplayer
  $text = str_replace('http://www.nmc.edu/', 'https://www.nmc.edu/', $text);
  $text = str_replace('http%3A%2F%2Fwww%2Enmc%2Eedu', 'https%3A%2F%2Fwww%2Enmc%2Eedu', $text);

return $text;
}
?>
