<?php

use Jefyokta\Json2Tex\Converter;

require_once "vendor/autoload.php";

$json = json_decode(file_get_contents("ex.json"));
$converter = Converter::setContent($json->contents);


file_put_contents('result.html', $converter->getHtml());
// file_put_contents('result.tex', $converter->getLatex());

