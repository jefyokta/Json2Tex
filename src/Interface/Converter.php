<?php

namespace Jefyokta\Json2Tex\Interface;
use Jefyokta\Json2Tex\Type\Node;
interface Converter
{
    /**
     * @param Node $node
     * @return string
     */
    public function paragraph($node);

    /**
     * @param Heading $content
     * @return string
     */
    public function heading($content);

    /**
     * @param Node $content
     * @return string
     */
    public function inlineMath($content);

    /**
     * @param Node $content
     * @return string
     */
    public function blockMath($content);

    /**
     * @param Node $content
     * @return string
     */
    public function orderedList($content);

    /**
     * @param Node $content
     * @return string
     */
    public function bulletList($content);

    /**
     * @param Node $content
     * @return string
     */
    public function listItem($content);

    /**
     * @param object{attrs:object{src:string,caption:string}} $element
     * @return string
     */
    public function figure($element);

    /**
     * @param Node $element
     * @return string
     */
    public function text($element);

    /**
     * @param Node $element
     * @return string
     */
    public function image($element);

    /**
     * @param Node $element
     * @return string
     */
    public function cite($element);

    /**
     * @param Node $element
     * @return string
     */
    public function var($element);

    /**
     * @param string $text
     * @return string
     */
    public function italic($text);

    /**
     * @param string $text
     * @return string
     */
    public function bold($text);

    /**
     * @param string $text
     * @return string
     */
    public function comment($text);

    /**
     * @param Table $element
     * @return string
     */
    public function table($element);


    /**
     * @param \Jefyokta\Json2Tex\Type\Node $element
     */
    public function tableCell($element);
 /**
     * @param \Jefyokta\Json2Tex\Type\Node $element
     */
    public function tableRow($element);

    public function tableHeader();
}
