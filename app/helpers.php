<?php
/**
 * Created by PhpStorm.
 * User: ksy
 * Date: 19. 3. 8
 * Time: 오전 9:52
 */

if(! function_exists('markdown')) {
    function markdown($text=null) {
        return app(ParsedownExtra::class)->text($text);
    }
}