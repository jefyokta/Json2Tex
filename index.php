<?php

use Jefyokta\Json2Tex\Citation;

require_once "vendor/autoload.php";
Citation::set('dan','dkk.');
echo Citation::formatAuthorName('Doe, John and Smith, Jane and Tanaka, Hiroshi').PHP_EOL;
echo Citation::formatAuthorName("Doe, John and Smith, Jane and Tanaka, Hiroshi and Lee, Anna and Kim, Min and Zhang, Wei");


// $json = json_decode(file_get_contents("ex.json"));
// $converter = Converter::setContent($json->contents);


// file_put_contents('result.html', $converter->getHtml());
// // file_put_contents('result.tex', $converter->getLatex());

