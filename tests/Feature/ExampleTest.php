<?php

use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\HtmlConverter;
use Jefyokta\Json2Tex\HtmlTableOfContentConverter;

require_once __DIR__ . "/../../vendor/autoload.php";


test("converting to html", function () {

    // $result = Converter::setContent(json_decode(file_get_contents(__DIR__ . "/../../ex.json"))->contents)->getHtml();

    expect(true)->toBe(true);
});
test('registering html convrter', function () {


    $json = json_encode([[
                'type' => 'text',
                'text' => 'hello world'
    ]]);
    $result = Converter::setContent(json_decode($json))->getHtml();

    expect($result)->toBe('hello world');
});

test('html toc', function () {

    // $toc = new HtmlTableOfContentConverter();

    // $result =  $toc->render(json_decode(file_get_contents(__DIR__ . "/../../ex.json"))->contents);

    // echo $result;

    expect(true)->toBe(true);
});


test("Toc Rendering with Group",function(){

    $converter = new HtmlTableOfContentConverter;

});