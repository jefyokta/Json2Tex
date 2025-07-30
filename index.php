<?php

use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\HtmlTableOfContentConverter;

require_once __DIR__ . "/vendor/autoload.php";



$raw = file_get_contents(__DIR__ . "/ex.json");
$converter = new HtmlTableOfContentConverter();

$nodes = json_decode($raw);
$toc = $converter->render($nodes);


$toc .= Converter::getHtml($nodes);

file_put_contents('finalp.html', $toc);
