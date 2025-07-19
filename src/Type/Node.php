<?php

namespace Jefyokta\Json2Tex\Type;


/**
 * @template T
 */

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
    /** @var T */
    public $attrs;
    public string $text;
};
