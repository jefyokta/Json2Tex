<?php

namespace Jefyokta\Json2Tex\Interface;

use Jefyokta\Json2Tex\Type\Node;
use Jefyokta\Json2Tex\Type\Table;
use Jefyokta\Json2Tex\Type\Heading;
use Figure;

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

  /**e
   * @param Node $content
   * @return string
   */
  public function inlineMath($content);

  /**
   * @param Node $content
   * @return string
   */
  public function mathBlock($content);

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
   * @param Figure $element
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
   * @param \Jefyokta\Json2Tex\Type\Cite $element
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
   * @param \Jefyokta\Json2Tex\Type\Cell $element
   */
  public function tableCell($element);
  /**
   * @param \Jefyokta\Json2Tex\Type\Node $element
   */
  public function tableRow($element);

  /**
   * @param \Jefyokta\Json2Tex\Type\Cell $element
   */
  public function tableHeader($element);

  /**
   * @param Figure $element
   */
  public function imageFigure($element);

  /**
   * @param Node $element
   */
  public function figcaption($element);

  public function hasMethod(string $method):bool;
}
