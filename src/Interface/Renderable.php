<?php

namespace Jefyokta\Json2Tex\Interface;
interface Renderable
{
    public function render(&$nodes):string;
}
