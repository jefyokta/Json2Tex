<?php


use Jefyokta\Json2Tex\Citation;

require_once __DIR__ . "/../../vendor/autoload.php";


test("3 author",function(){

    $result = Citation::formatAuthorName('Doe, John and Smith, Jane and Tanaka, Hiroshi');

    expect($result)->toBe("Doe, Smith and Tanaka");

});

test("5+ author",function(){
    $result = Citation::formatAuthorName('Doe, John and Smith, Jane and Tanaka, Hiroshi and Lee, Anna and Kim, Min and Zhang, Wei');

    expect($result)->toBe("Doe, Smith, Tanaka, Lee, Kim et al.");
});


