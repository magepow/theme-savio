<?php
/**
 * @Author: nguyen
 * @Date:   2020-02-12 14:01:01
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-10-29 17:18:45
 */

namespace Magiccart\Alothemes\Helper;

class Lazyload extends \Magento\Framework\App\Helper\AbstractHelper
{

    public function addLazyloadImage($content, $placeholder, $isJson=false)
    {
        if($isJson) return  $this->addLazyloadImageJson($content, $placeholder);
        return preg_replace_callback(
            '/<img\s*.*?(?:class="(.*?)")?([^>]*)>/',
            function($match) use ($placeholder) {

                if(stripos($match[0], ' data-src="') !== false) return $match[0];
                if(stripos($match[0], ' class="') !== false){
                    $lazy = str_replace(' class="', ' class="lazyload ', $match[0]); 
                }else {
                    $lazy = str_replace('<img ', '<img class="lazyload" ', $match[0]);
                }

                /* break if exist data-src */
                // if(strpos($lazy, ' data-src="')) return $lazy;

                return str_replace(' src="', ' src="' .$placeholder. '" data-src="', $lazy);
            },
            $content
        );        
    }

    public function addLazyloadImageJson($content, $placeholder)
    {
        $placeholder = addslashes($placeholder); 
        return preg_replace_callback(
            '/<img\s*.*?(?:class=\\\"(.*?)\\\")?([^>]*)>/',
            function($match) use ($placeholder) {
                
                if(stripos($match[0], ' data-src=\"') !== false) return $match[0];
                if(stripos($match[0], ' class="') !== false){
                    $lazy = str_replace(' class=\"', ' class=\"lazyload ', $match[0]); 
                }else {
                    $lazy = str_replace('<img ', '<img class=\"lazyload\" ', $match[0]);
                }

                /* break if exist data-src */
                // if(strpos($lazy, ' data-src=\"')) return $lazy;

                return str_replace(' src=\"', ' src=\"' . $placeholder . '\" data-src=\"', $lazy);
            },
            $content
        );        
    }

    public function notLazyloadImage($content, $isJson=false)
    {
        if($isJson) return  $this->notLazyloadImageJson($content);
        return preg_replace_callback(
            '/<img\s*.*?(?:class="(.*?)")?([^>]*)>/',
            function($match) {

                if(stripos($match[0], ' data-src="') !== false) return $match[0];
                if(stripos($match[0], ' class="') !== false){
                    $lazy = str_replace(' class="', ' class="notlazy ', $match[0]); 
                }else {
                    $lazy = str_replace('<img ', '<img class="notlazy" ', $match[0]);
                }
                return $lazy;
            },
            $content
        );        
    }

    public function notLazyloadImageJson($content)
    {
        $placeholder = addslashes($placeholder); 
        return preg_replace_callback(
            '/<img\s*.*?(?:class=\\\"(.*?)\\\")?([^>]*)>/',
            function($match) {
                
                if(stripos($match[0], ' data-src=\"') !== false) return $match[0];
                if(stripos($match[0], ' class="') !== false){
                    $lazy = str_replace(' class=\"', ' class=\"notlazy ', $match[0]); 
                }else {
                    $lazy = str_replace('<img ', '<img class=\"notlazy\" ', $match[0]);
                }
                return $lazy;
            },
            $content
        );        
    }

}
