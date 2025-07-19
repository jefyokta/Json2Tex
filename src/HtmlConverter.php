<?php

namespace Jefyokta\Json2Tex;

use Error;
use Jefyokta\Json2Tex\Converter as Json2TexConverter;
use Jefyokta\Json2Tex\Interface\Converter;
use Jefyokta\Json2Tex\Type\Node;

class HtmlConverter implements Converter
{
    /**
     * @var array<string,callback>
     */
    private static $custom;

    /**
     * @param Node[] $nodes
     */



    private function getHtmlContent($nodes)
    {
        if (!$nodes) {
            return '';
        }
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
        return "<h{$content->attrs->level} id=\"{$content->attrs->id}\">{$this->getHtmlContent($content->content ?? '')}</h{$content->attrs->level}>";
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

        $cites = Citation::getCollection();

        $cite =   array_find($cites, function ($c) use ($element) {
            return $c['cite'] == $element->attrs->cite;
        });
        $name = Citation::formatAuthorName($cite['author']);
        $content = $element->citeA ? "{$name} ({$cite['year']})" : "({$name} ,{$cite['year']})";
        return "<cite id=\"{$element->attrs->cite}\">{$content}</cite>";
    }
    public function blockMath($content)
    {

        return "<div style='display:flex;justify-content:center'><div>" .  Math::render($content->attrs?->latex ?? '') . "<div></div>";
    }
    public function mathBlock($content)
    {

        return "<div style='display:flex;justify-content:center'><div>" .  Math::render($content->attrs?->latex ?? '') . "<div></div>";
    }

    public function tableCell($element)
    {
        $rowSpan = $element->attrs->rowspan ?? 1;
        $colSpan = $element->attrs->colspan ?? 1;
        $width =  $element->attrs->colwidth[0] ?? false;
        $width = $width ? $width . "px" : "auto";
        $content =  $element->content;

        return "<td colspan=\"$colSpan\" rowspan=\"$rowSpan\" style=\"width:{$width}\">  
         <div style=\"display:flex;flex-direction:column;justify-content:center;align-items:{$this->getCellalignment($element->attrs->align)}\">
        {$this->getHtmlContent($content)}
        </div><td>";
    }

    public function tableHeader($element)
    {

        $rowSpan = $element->attrs->rowspan;
        $colSpan = $element->attrs->colspan ?? 1;
        $width =  $element->attrs->colwidth[0];
        $width = $width ? $width . "px" : "auto";

        $content =  $element->content;

        return "<th colspan=\"$colSpan\" rowspan=\"$rowSpan\" style=\"width:{$width}\">
        <div style=\"display:flex;flex-direction:column;justify-content:center;align-items:{$this->getCellalignment($element->attrs->align)}\">
        {$this->getHtmlContent($content)}
        </div>
        <th>";
    }

    public function tableRow($element)
    {


        return "<tr>" . $this->getHtmlContent($element->content) . "</tr>";
    }
 
    public function figureImage($element)
    {

        return "<figure>{$this->getHtmlContent($element->content)}<figure>";
    }

    private function getCellAlignment(string $alignment): string
    {
        switch ($alignment) {
            case 'left':
                $align = "start";
                break;
            case 'center':
                $align = "center";
                break;

            default:
                $align = "start";

                break;
        }

        return $align;
    }




    public function figureTable($element)
    {
        return "<figure id='{$element->attrs->figureId}'>{$this->getHtmlContent($element->content)}<figure>";
    }

    public function imageFigure($element)
    {
        return "<figure figureId=\"{$element->attrs->figureId}\" id=\"{$element->attrs->figureId}\" style=\"width:100%;display:flex;flex-direction:column;align-items:center;\">{$this->getHtmlContent($element->content)}</figure>";
    }


    public function figcaption($element)
    {
        return "<figcaption>{$this->getHtmlContent($element->content)}</figcaption>";
    }

    public function __call($name, $arguments)
    {
        return static::$custom[$name](...$arguments);
    }

    /**
     * @param string $name
     * @param callable(\Jefyokta\Json2Tex\Type\Node): string $callback
     */

    public static function register($name, $callback)
    {

        if (method_exists(new static(), $name)) {
            throw new Error("method {$name} is already exists!");
        }

        static::$custom[$name] = $callback;
    }

    public function hasMethod(string $method): bool
    {
        return method_exists($this, $method) || isset(static::$custom[$method]);
    }
}
