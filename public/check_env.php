<?php
$lines = file(__DIR__.'/../.env');
foreach ($lines as $line) {
    if (strpos(trim($line), 'APP_URL=') === 0) {
        echo $line;
        break;
    }
}
