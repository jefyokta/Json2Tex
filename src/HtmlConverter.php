<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Converter as Json2TexConverter;
use Jefyokta\Json2Tex\Interface\Converter;
use Jefyokta\Json2Tex\Type\Node;

class HtmlConverter implements Converter
{

    /**
     * @param Node[] $nodes
     */
    private function getHtmlContent($nodes)
    {

        return  Json2TexConverter::getHtml($nodes);
    }
    public function paragraph($node)
    {

        return "<p>" . (isset($node->content) ? $this->getHtmlContent($node->content) : "") . "</p>";
    }

    public function table($element)
    {

        return "<table>" . $this->getHtmlContent($element->content) . "</table>";
    }

    public function figure($element)
    {

        return "<figure id='{$element->attrs->figureId}'>{$this->getHtmlContent($element->content)}<figure>";
    }

    public function heading($content)
    {
        return "<h{$content->attrs->level}>{$this->getHtmlContent($content->content)}</h{$content->attrs->level}>";
    }
    public function listItem($content)
    {

        return "<li>{$this->getHtmlContent($content->content)}</li>";
    }

    public function orderedList($content)
    {

        return "<ol>{$this->getHtmlContent($content->content)}</ol>";
    }
    public function italic($text)
    {

        return "<em>$text</em>";
    }

    public function image($element)
    {

        return "<img src=\"{$element->attrs->src}\" style=\"width:{$element->attrs->width}\">";
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

    public function bulletList($content)
    {

        return "<ul>{$this->getHtmlContent($content->content)}</ul>";
    }

    public function comment($text) {}

    public function inlineMath($content)
    {

        return "<span>" . Math::render($content->text) . "<span>";
    }

    public function cite($element)
    {
        return "<cite>{$element->text}</cite>";
    }

    public function blockMath($content) {}

    public function tableCell($element)
    {

        $rowSpan = $element->attrs->rowspan ?? 1;
        $colSpan = $element->attrs->colspan ?? 1;
        $width =  $element->attrs->colwidth[0];
        $content =  $element->content;

        return "<td colspan=\"$colSpan\" rowspan=\"$rowSpan\" style=\"width:{$width}px\">{$this->getHtmlContent($content)}<td>";
    }

    public function tableHeader($element) {

        $rowSpan = $element->attrs->rowspan;
        $colSpan = $element->attrs->colspan ?? 1;
        $width =  $element->attrs->colwidth[0];
        $content =  $element->content;

        return "<th colspan=\"$colSpan\" rowspan=\"$rowSpan\" style=\"width:{$width}px\">{$this->getHtmlContent($content)}<th>";
    }

    public function tableRow($element)
    {


        return "<tr>" . $this->getHtmlContent($element->content) . "</tr>";
    }

    public function figureImage()
    {

        return "";
    }




    public function figureTable($element)
    {
        return "<figure id='{$element->attrs->figureId}'>{$this->getHtmlContent($element->content)}<figure>";
    }
}
