<?php

namespace Jefyokta\Json2Tex\Type;


interface Node
{
    /**
     * @var Node[] $content
     */
    public array $content;
    /**
     * @var array<int, object{type: string}> $marks
     */
    public array $marks;
    public string $type;
    public $attrs;
    public string $text;
};
