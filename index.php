<?php

require __DIR__ . '/vendor/autoload.php';

$temp = new \Stagaires\PhpTemplate\Template(__DIR__ . '/views');

$temp->render('test', ['test' => 'test', 'test2' => 'test2', 'products' => ['test', 'test2', 'test3'] ]);


//, 'test2' => 'test2', 'test3' => '<script>{setTimeout(function() { alert("hoi"); }, 5000);}</script>;'