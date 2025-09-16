<?php

namespace Jefyokta\Json2Tex\List;

class Reference
{

    //to do,
    // build a reference list, but is not from node so i cant user renderable interface
    /**
     * @param string[] $cites
     */
    public function render($cites)
    {
        $result = '<ul>';
        foreach ($cites as $cite) {
            $result .= "<li>$cite</li>";
        }
        $result .= "</ul>";
        return '';
    }
}
