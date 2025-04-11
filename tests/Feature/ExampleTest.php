<?php

use Jefyokta\Json2Tex\Converter;

require_once __DIR__ . "/../../vendor/autoload.php";


test("converting to html", function () {

    $result = Converter::setContent(json_decode(file_get_contents(__DIR__ . "/../../ex.json"))->contents)->getHtml();

    expect($result)->toBe(file_get_contents(__DIR__."/../../result.html"));
});
