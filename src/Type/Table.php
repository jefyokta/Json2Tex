<?php

namespace Jefyokta\Json2Tex\Type;


interface Table extends Node
{
    /**
     * @var array<int,TableRow> $content
     */
    public array $content;
};
