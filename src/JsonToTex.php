<?php

namespace Jefyokta\Json2Tex;

use Error;
use Jefyokta\Json2Tex\Collector\BaseCollector;
use Jefyokta\Json2Tex\Exception\UnregisteredObserver;
use Jefyokta\Json2Tex\Interface\Converter;
use Jefyokta\Json2Tex\Interface\Observer;
use Jefyokta\Json2Tex\Type\Node;

class JsonToTex
{
    /**
     * @var Converter
     */
    private Converter $converter;

    private static $chapterCounter = 0;

    /**
     * @var Node[]
     */
    private array $contents = [];
    /**
     * @var array<class-string<BaseCollector>,BaseCollector>
     */
    private static $observers = [];

    /**
     *  Immediately get latex content.
     *
     * @param string $json
     * @return string
     */
    public static function compile(string $json): string
    {
        $decoded = json_decode($json);
        return (new static())->setContent($decoded->content ?? [])->getLatex();
    }

    /**
     * Set the tiptap json content
     *
     * @param Node[] $contents
     * @return static
     */
    public function setContent(array $contents): static
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * Return Html Output
     *
     * Once the contents is null, it will use this->contents
     * @param Node[]|null $contents
     * @return string
     */
    public function getHtml(?array $contents = null): string
    {
        $this->converter = new HtmlConverter();
        return $this->getContent($contents);
    }

    /**
     * Get Latex Result.
     *
     * Once the contents is null, it will use this->contents
     * @param Node[]|null $contents
     * @return string
     */
    public function getLatex(?array $contents = null): string
    {
        $this->converter = new LatexConverter();
        return $this->getContent($contents ?? $this->contents);
    }

    /**
     * Convert content using used concerter.
     *
     * @param Node[]|null $contents
     * @return string
     */
    private function getContent(?array $contents = null): string
    {
        $contents = $contents ?? $this->contents;
        $result = '';
        if (!$this->converter) {
            $this->converter = new LatexConverter;
        }
        foreach ($contents as $content) {
            foreach (static::$observers as $observer) {
                // if ($content->type == 'heading' && $content->attrs->level == 1) {
                //     static::$chapterCounter++;
                // }
                $observer->onNode($content);
            }
        }
        foreach ($contents as $content) {
            $content = is_object($content) ? $content : (object) $content;

            if ($this->converter->hasMethod($content->type)) {
                $result .= $this->converter->{$content->type}($content);
            }
        }

        return $result;
    }

    public function createHtmlToc(&$nodes = null)
    {
        $toc = new HtmlTableOfContentConverter;
        return $toc->render($nodes ?? $this->contents);
    }

    function getKatexStyle()
    {
        return $style = __DIR__ . "/dist/dist/katex.min.css";
    }
    function observe(BaseCollector $observer)
    {
        static::$observers[(string)$observer] = $observer;

        return $this;
    }

    static function getChapterCounter(){

        return self::$chapterCounter;
    }

    static function getObserverInstance(string $classname)
    {
        if (! isset(static::$observers[$classname])) {

            throw new UnregisteredObserver("Current generator is not using observer `{$classname}` , please add it first");
        }
        return static::$observers[$classname];
    }
}
