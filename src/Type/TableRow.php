<?php

namespace Jefyokta\Json2Tex\Type;

interface TableRow extends Node
{

    public string $type = "tableRow";
    /**
     * @var array<int,TableCell>
     */
    public array $content;
}

interface TableCell extends Node
{
    /**
     * @var object{colwidth:int[],align:string,colspan:int,rowspan:int}
     */
    public $attrs;
}
