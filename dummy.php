<?php
function query_filter($query)
{
  $key_words = explode(' ',$query);
  $selected_words = array();

  for ($i=0; $i < count($key_words) ; $i++) {
    if (strlen($key_words[$i]) < 4) {
      continue;
    } else {
      array_push($selected_words, $key_words[$i]);
    }
  }
  return $selected_words;
}

$string = "Good morning ma People, is a good day";
var_dump(query_filter($string));
exit;
?>
