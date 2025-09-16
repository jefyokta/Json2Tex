<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Interface\Renderable;
use Jefyokta\Json2Tex\Toc\HeadingGroup;
use Jefyokta\Json2Tex\Type\Node;

class HtmlTableOfContentConverter implements Renderable
{
    /**
     * @var  string[]
     */
    private $slugs = [];

    private $classes = [];

    private $title = "DAFTAR ISI";
    private $titleId = 'toc';

    private $chapterCounter = 0;
    private $subChapterCounter = '';
    private $subSubChapterCounter = 0;

    private $preHeadings = [];


    /**
     * @param Node[] $nodes
     * @return string
     */

    public function render(&$nodes): string
    {
        $tocItems = array_merge($this->preHeadings, $this->extractHeadings($nodes));
        return $this->renderHtml($tocItems);
    }


    /**
     * @param array<int, array{level: int, text: string, id: string,is_pre:bool}>[] $preHeadings
     * 
     */
    function withPreheadings($preHeadings)
    {
        $this->preHeadings = $preHeadings;
    }

    function classificate($items) {}

    // function makeGroup

    /**
     * @param Node[] $nodes
     * @return array<int, array{level: int, text: string, id: string,is_pre:bool}>
     */
    public  function extractHeadings(array &$nodes): array
    {
        $headings = [];

        foreach ($nodes as &$node) {
            if ($node->type === 'heading') {
                if ($node->attrs->level > 3) {
                    continue;
                }
                $level = $node->attrs->level ?? 1;
                $text = $node->text ?? $this->extractText($node->content ?? []);
                $id = $this->slugify($text);
                $node->attrs->id = $id;
                $headings[] = [
                    'level' => $level,
                    'text' => $text,
                    'id' => $id,
                    "is_pre" => false
                ];
            }

            // if (!empty($node->content)) {
            //     $headings = array_merge($headings, $this->extractHeadings($node->content));
            // }
        }

        return $headings;
    }

    /**
     * @param Node[] $nodes
     */
    private function extractText(array $nodes): string
    {
        $text = '';

        $converter = new HtmlConverter;

        foreach ($nodes as $node) {
            $tmp = htmlspecialchars($node->text, ENT_QUOTES, 'UTF-8');
            if (!empty($node->marks)) {
                foreach ($node->marks as $mark) {
                    $converter->{$mark->type}($tmp);
                }
            }
            $text .= $tmp;
        }

        return $text;
    }



    /**
     * @param array<int, array{level: int, text: string, id: string,is_pre:bool}> $items
     */
    public function renderHtml(array $items): string
    {
        $html = "";
        $lastLevel = 0;

        foreach ($items as $item) {
            $diff = $item['level'] - $lastLevel;
            if ($item['level'] == 1 && !$item['is_pre']) {
                $this->chapterCounter++;
                $this->subChapterCounter = 0;
            }
            if ($item['level'] == 2) {
                $this->subChapterCounter++;
                $this->subSubChapterCounter = 0;
            }
            if ($item['level'] == 3) {
                $this->subSubChapterCounter++;
            }

            if ($diff > 0) {
                $html .= str_repeat("<ul>\n", $diff);
            } elseif ($diff < 0) {
                $html .= str_repeat("</ul>\n", -$diff);
            }

            $h = $item['level'] == 1 ? 2 : 3;
            $roman =  $item['is_pre'] ? "link-number-pra" : "link-number";
            $html .= "<li><h{$h}>
            
                <a href=\"#{$item['id']}\" class=\"page-num\">
                    <span>" . $this->getHtmlByLevel($item['level'], $item['text'], $item['is_pre']) . "</span>
                </a>
                <a class=\"$roman\" href=\"#{$item['id']}\"></a>
            </h{$h}>
            </li>\n";
            $lastLevel = $item['level'];
        }

        $html .= str_repeat("</ul>\n", $lastLevel - 1);
        $html .= "</ul>\n";
        $classes = !empty($this->classes) ? "class='" . implode(' ', $this->classes) . "'" : '';
        return "<div $classes> <h1 style=\"text-align:center;text-transform:capitalize;\" id=\"toc\">{$this->title}</h1>" . $html . "</div>";
    }

    /**
     * Convert string to slug for href anchors.
     */
    private function slugify(string $text): string
    {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $text), '-'));
        if (in_array($slug, $this->slugs)) {
            $slug = $this->slugify($slug . "x");;
        }
        $this->slugs[] = $slug;
        return $slug;
    }

    private function getHtmlByLevel($level,  $text, $pre): string
    {
        $html = '';
        if ($level === 1) {
            $html = $pre ? "<b class=\"pra\">$text</b>" : "<b><span class=\"toc-item-number\">{$this->chapterCounter}</span>$text</b>";
        } elseif ($level == 3) {
            $html = "<span style=\"font-weight:normal !important\"><span class=\"toc-item-number\">{$this->chapterCounter}.{$this->subChapterCounter}.{$this->subSubChapterCounter}</span>$text</span>";
        } else {
            $html = "<span style=\"font-weight:normal !important\"><span class=\"toc-item-number\">{$this->chapterCounter}.{$this->subChapterCounter}</span>$text</span>";
        }

        return    "<span class=\"toc-item\">$html</span>";
        // return $html;
    }

    /**
     * Title Classes
     */
    function withClasess(...$class)
    {
        $this->classes = $class;

        return $this;
    }

    function withTitle($title, $id = 'toc', ...$classes)
    {
        $this->title = $title;
        $this->titleId = $id;
        $this->classes = $classes;

        return $this;
    }

    function group($items)
    {

        /** @var HeadingGroup[] **/
        $groups = [];
        /** @var HeadingGroup */
        $lastGroup = null;

        foreach ($items as $item) {
            if ($item['level'] == 1) {
                if (null !== $lastGroup) {
                    $groups[] = $lastGroup;
                }
                $lastGroup = new HeadingGroup($item['text'], $item['id'], $item['is_pre']);
            } else {
                if (null !== $lastGroup) {
                    $lastGroup->addChild($item);
                }
            }
        }

        return $groups;
    }
}
