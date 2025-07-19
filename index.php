<?php

use Jefyokta\Json2Tex\HtmlTableOfContentConverter;
use Jefyokta\Json2Tex\JsonToTex;

require_once __DIR__ . "/vendor/autoload.php";



$raw = file_get_contents(__DIR__ . "/ex.json");
$converter = new HtmlTableOfContentConverter();

$nodes = json_decode($raw);
$toc = $converter->render($nodes);


$jt = new JsonToTex;
$toc .= $jt->getHtml($nodes);

file_put_contents('finalp.html', $toc);
