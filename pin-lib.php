<?php

function create_pin($min, $max)
{
    if (! file_exists (backend()))
        reset_backend();

    $pin        = random_number($min, $max);
    $attempts   = 1;

    while ((! acceptable ($pin)) && (++$attempts <= $max))
    {
        $pin = random_number($min, $max);
    }

    // have tried all possible values;
    // if there should be at least one acceptable value
    // then its safe to assume that all the acceptable values are already assigned
    // therefore : reset the 'backend' and start over again!
    if ($attempts >= $max)
    {
        reset_backend ();
        $pin = create_pin($min, $max);
    }
    else
    {
        save_pin ($pin);
    }

    return $pin;
}

// 5 distinct checks for bsns logic acceptability :
// - four didgts long
// - not a numeric sequence (eg 0123)
// - not a reverse numeric sequence (eg 3210)
// - not the same digit four times (eg 1111)
// - not previously issued

function acceptable ($pin)
{
    if (strlen ($pin) < 4)
        return false;
    
    // check for sequential number
    $sequence = '0123456789012';
    if (strpos($sequence,         strval($pin)) !== false)
        return false;

    // check for reverse sequential number
    if (strpos(strrev($sequence), strval($pin)) !== false)
        return false;

    // check for all same digits
    // regexp matches $pin which :
    //      begins with a digit ([0-9]),
    //      followed by 0 or more (*) of
    //      same digit (\1)
    //      until EOL ($)
    if (preg_match('/^([0-9])\1*$/', $pin))
        return false;

    // check for already used number
    if (in_array($pin, file(backend())))
        return false;

    return true;
}

function save_pin ($pin)
{
    file_put_contents(backend(), "$pin\n", FILE_APPEND);
}

function reset_backend ()
{
    file_put_contents(backend(), '');
}

function random_number ($a = 0000, $b = 9999)
{
    return rand($a, $b);
}

function backend()
{
    return 'pins.txt';
}

?>
