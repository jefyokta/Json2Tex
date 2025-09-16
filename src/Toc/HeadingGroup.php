<?php

namespace Jefyokta\Json2Tex\Toc;

use Jefyokta\Json2Tex\Exception\InvalidHeadingLevel;

class HeadingGroup
{


    /**
     * @var  array<int,array{level: int, text: string, id: string,is_pre:bool}> $items
     */
    private $children = [];

    public function __construct(private $text, private $id, private $is_pre) {}
    /**
     * @param  array{level: int, text: string, id: string,is_pre:bool} $item
     */
    function addChild($item)
    {

        $this->children[] = $item;
    }

    /**
     * @param  array<int,array{level: int, text: string, id: string,is_pre:bool}> $items
     */
    function setChildren(array $items)
    {
        $this->children = $items;
    }
    /**
     * @return  array<int,array{level: int, text: string, id: string,is_pre:bool}> $items
     */
    function getChildren()
    {
        return $this->children;
    }

    function getMainHeading()
    {

        return [
            "text" => $this->text,
            "id" => $this->id,
            "level" => 1,
            "is_pre" => $this->is_pre
        ];
    }
}
