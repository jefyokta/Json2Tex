<?php 

namespace Jefyokta\Json2Tex\Type;

interface Cell extends Node{
    /**
     * @var object{colspan:int,rowspan:int,colwidth:int[],align:string}
     */
    public $attrs;
}
?>