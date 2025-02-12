<?php

require_once __DIR__.'/src/JsonToTex.php';
require_once __DIR__.'/src/Converter.php';

use Jefyokta\Json2Tex\JsonToTex;


$jtex= new JsonToTex(json_encode(json_decode(file_get_contents('ex.json'))->json));

echo $jtex->compile();
