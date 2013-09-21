<?php 

/**
 * This file is part of the Ksn135HtpasswdBundle package.
 *
 * (c) Serg N. Kalachev <serg@kalachev.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    $autoload = require_once $file;
} else {
    throw new RuntimeException('Install dependencies to run test suite.');
}
