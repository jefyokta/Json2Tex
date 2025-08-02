<?php

use Jefyokta\Json2Tex\Collector\ImageCollector;
use Jefyokta\Json2Tex\Collector\TableCollector;
use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\Interface\Observer;

// Converter::createHtmlToc()


require_once __DIR__ . "/vendor/autoload.php";

$raw = file_get_contents(__DIR__ . "/ex.json");

$nodes = json_decode($raw);


$html = Converter::setContent($nodes)->observe(new TableCollector)->getHtml();

file_put_contents('result.html',$html);