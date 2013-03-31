<?php

require_once('pin-lib.php');

$pin = 0000;
$max = 9999;

while ($pin <= $max)
{
    echo "[$pin] : " . ((acceptable($pin)) ? 'accept' : 'not') . "\n";

    if (acceptable($pin))
        save_pin($pin);

    $pin ++;
}


?>
