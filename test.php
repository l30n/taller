<?php
$content = file_get_contents('./inserts.txt');
$inserts = explode(';', $content);
var_dump($inserts);
