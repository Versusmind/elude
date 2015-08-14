<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 14/08/15
 * Time: 11:41
 */

namespace App\Libraries\Assets;

class Rewriter
{

    protected $content;

    /**
     * Rewriter constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function process()
    {
        $newContent = preg_replace_callback('`url\((.*?)\)`s', function($matches) {
            if (strpos($matches[0], '://') !== false) {
                return $matches[0];
            }
            $matches[1] = str_replace(['"', "'", '../'], '', $matches[1]);

            return "url(/assets/" . $matches[1] . ")";

        }, $this->content);

        return $newContent;
    }
}