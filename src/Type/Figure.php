<?php

use Jefyokta\Json2Tex\Type\Node;

interface Figure extends Node{
    /**
     * @var object{figureId:string}
     */
    public $attrs;

} 