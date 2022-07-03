<?php

use Symfony\Component\ErrorHandler\Debug;

Debug::enable();
session_start();

require __DIR__.'/constants.php';
require __DIR__.'/../app/Http/Kernel.php';
require __DIR__.'/../routes/setup.php';