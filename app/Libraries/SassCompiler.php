<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 17/04/16
 * Time: 15:21
 */

namespace App\Libraries;


use Leafo\ScssPhp\Compiler;

class SassCompiler extends Compiler
{
    protected function matchExtends($selector, &$out, $from = 0, $initial = true)
    {
        foreach ($selector as $i => $part) {
            if ($i < $from) {
                continue;
            }

            if ($this->matchExtendsSingle($part, $origin)) {
                $before = array_slice($selector, 0, $i);
                $after = array_slice($selector, $i + 1);

                foreach ($origin as $new) {
                    $k = 0;

                    // remove shared parts
                    if ($initial) {
                        while (isset($new[$k]) && isset($before[$k]) && $before[$k] === $new[$k]) {
                            $k++;
                        }
                    }

                    $result = array_merge(
                        $before,
                        $k > 0 ? array_slice($new, $k) : $new,
                        $after
                    );

                    if ($result == $selector) {
                        continue;
                    }

                    $out[] = $result;

                    // recursively check for more matches
                    $this->matchExtends($result, $out, $i, false);

                    // selector sequence merging
                    if (! empty($before) && count($new) > 1) {
                        $result2 = array_merge(
                            array_slice($new, 0, -1),
                            $k > 0 ? array_slice($before, $k) : $before,
                            array_slice($new, -1),
                            $after
                        );

                        $out[] = $result2;
                    }
                }
            }
        }
    }
}