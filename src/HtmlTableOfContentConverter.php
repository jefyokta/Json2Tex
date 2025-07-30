<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Interface\Renderable;
use Jefyokta\Json2Tex\Type\Node;

class HtmlTableOfContentConverter implements Renderable
{
    /**
     * @var  string[]
     */
    private $slugs = [];

    /**
     * @param Node[] $nodes
     * @return string
     */


    public function render(&$nodes): string
    {
        $tocItems = $this->extractHeadings($nodes);
        return $this->renderHtml($tocItems);
    }

    /**
     * @param Node[] $nodes
     * @return array<int, array{level: int, text: string, id: string}>
     */
    private function extractHeadings(array &$nodes): array
    {
        $headings = [];

        foreach ($nodes as &$node) {
            if ($node->type === 'heading') {
                $level = $node->attrs->level ?? 1;
                $text = $node->text ?? $this->extractText($node->content ?? []);
                $id = $this->slugify($text);
                $node->attrs->id = $id;
                $headings[] = [
                    'level' => $level,
                    'text' => $text,
                    'id' => $id,
                ];
            }

            if (!empty($node->content)) {
                $headings = array_merge($headings, $this->extractHeadings($node->content));
            }
        }

        return $headings;
    }

    /**
     * @param Node[] $nodes
     */
    private function extractText(array $nodes): string
    {
        $text = '';

        foreach ($nodes as $node) {
            $text .= $node->text ?? $this->extractText($node->content ?? []);
        }

        return trim($text);
    }

    /**
     * @param array<int, array{level: int, text: string, id: string}> $items
     */
    private function renderHtml(array $items): string
    {
        $html = "";
        $lastLevel = 0;

        foreach ($items as $item) {
            $diff = $item['level'] - $lastLevel;

            if ($diff > 0) {
                $html .= str_repeat("<ul>\n", $diff);
            } elseif ($diff < 0) {
                $html .= str_repeat("</ul>\n", -$diff);
            }

            $html .= "<li><a href=\"#{$item['id']}\" class=\"page-num\" data-ref=\"{$item['text']}\"></a></li>\n";
            $lastLevel = $item['level'];
        }

        $html .= str_repeat("</ul>\n", $lastLevel - 1);
        $html .= "</ul>\n";

        return $html;
    }

    /**
     * Convert string to slug for href anchors.
     */
    private function slugify(string $text): string
    {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $text), '-'));
        if (in_array($slug, $this->slugs)) {
            $slug = $slug . date('u');
        }
        $this->slugs[] = $slug;
        return $slug;
    }
}
