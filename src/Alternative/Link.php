<?php

namespace Jefyokta\Json2Tex\Alternative;
//this class requir paged js
class Link
{
    public static function style(): string
    {
        return <<<HTML
        <style>
        a.ref-image::before {
            content: "Gambar " target-counter(attr(href), fig-counter);
        }
        a.ref-table::before {
            content: "Tabel " target-counter(attr(href), caption-counter);
        }
        </style>
HTML;
    }

    public static function ref(object $node): string
    {
        // var_dump($node);
        $attrs = is_array($node->attrs ?? null) ? $node->attrs : $node->attrs;

        $isImage = $attrs->ref === "imageFigure";

        $class = $isImage ? "ref-image" : "ref-table";

        return "<a class=\"{$class}\" href=\"#{$attrs->link}\" class=\"link\"></a>";
    }
}
