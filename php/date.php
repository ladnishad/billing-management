<?php

$date = date('m/d/y');
function convert($date)
{
  return str_replace("/", "", $date);
}

$cleanDate = convert($date);

echo $cleanDate;

 ?>
