<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Interface\Converter;

class HtmlConverter implements Converter
{


    public function paragraph($node) {}

    public function table($element) {}

    public function figure($element) {}

    public function heading($content) {}
    public function listItem($content)
    {
        $contents = $content->content;
        $item = "";
        foreach ($contents as $c) {
            $item .= "<li>" . JsonToTex::setContent($c->content)->getHtml() . "</li>";
        }
        return "<ul>
        $item
        </ul>";
    }

    public function orderedList($content) {}
    public function italic($text)
    {

        return "<em>$text</em>";
    }

    public function image($element)
    {

        return "<img src=\"$element->attrs->src\">";
    }

    public function var($element) {}

    public function bold($text)
    {

        return "<b>$text</b>";
    }

    public function text($element)
    {
        $element->text = htmlspecialchars($element->text, ENT_QUOTES, 'UTF-8');
        if (!empty($element->marks)) {
            foreach ($element->marks as $mark) {
                if (method_exists($this, $mark->type)) {
                    $element->text = $this->{$mark->type}($element->text);
                }
            }
        }
        return $element->text;
    }

    public function bulletList($content) {}

    public function comment($text) {}

    public function inlineMath($content)
    {

        return "<span>" . Math::render($content->text) . "<span>";
    }

    public function cite($element) {
        return "<cite>{$element->text}</cite>";
    }

    public function blockMath($content) {}

    public function tableCell($element)
    {

        $rowSpan = $element->attrs->rowspan;
        $colSpan = $element->attrs->colspan;
        $width =  $element->attrs->width[0];
        $content =  $element->content;

        return "<td colspan=\"$colSpan\" rowspan=\"$rowSpan\">$content<td>";
    }

    public function tableHeader() {}

    public function tableRow($element)
    {


        return "<tr>{$element->content}</tr>";
    }

    public function figureImage() {}

    public function figureTable() {}
}
