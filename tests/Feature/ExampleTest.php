<?php

use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\HtmlConverter;
use Jefyokta\Json2Tex\HtmlTableOfContentConverter;

require_once __DIR__ . "/../../vendor/autoload.php";


test("converting to html", function () {

    $result = Converter::setContent(json_decode(file_get_contents(__DIR__ . "/../../ex.json"))->contents)->getHtml();

    expect(true)->toBe(true);
});
test('registering html convrter', function () {
    HtmlConverter::register('page', function ($ellement) {
        echo 'executed';
        return Converter::getHtml($ellement->content);
    });

    $json = json_encode([(object)[
        'type' => 'page',
        'content' => [
            (object)[
                'type' => 'text',
                'text' => 'hello world'
            ]
        ]
    ]]);
    $result = Converter::setContent(json_decode($json))->getHtml();

    expect($result)->toBe('hello world');
});

test('html toc', function () {

    $toc = new HtmlTableOfContentConverter();

    $result =  $toc->render(json_decode(file_get_contents(__DIR__ . "/../../ex.json"))->contents);

    echo $result;

    expect(true)->toBe(true);
});
