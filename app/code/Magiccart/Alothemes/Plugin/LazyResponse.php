<?php
/**
 * @Author: nguyen
 * @Date:   2020-02-12 14:01:01
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-02-12 21:14:03
 */

namespace Magiccart\Alothemes\Plugin;

use Magento\Framework\App\Response\Http;

class LazyResponse
{ 
    public $helper;
    
    public function __construct(
        \Magiccart\Alothemes\Helper\Data $helper,
        array $data = []
    ) {    
        $this->helper = $helper;

    }

    /**
     * @param Http $subject
     * @return void
     */
    public function beforeSendResponse(Http $response)
    {
        if(!$this->helper->getConfig('alothemes/preload/loading_img')) return;
        $body = $response->getBody();
        $body = $this->addLazyload($body);
        $response->setBody($body);
    }

    public function addLazyload($content) 
    {
        $placeholder = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGP6zwAAAgcBApocMXEAAAAASUVORK5CYII=';
        $content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-src="${2}"${3}>', $placeholder ), $content );
        $content = preg_replace( '#<img([^>]+?)class=[\'"]?([^>]*)>#', '<img${1}class="lazyload ${2}>', $content );
        $content = preg_replace('/<img ((?!class).)/s', '<img class="lazyload" $1', $content);
        return $content;
    }

    public function addLazyloadJs($content) 
    {
        $placeholder = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGP6zwAAAgcBApocMXEAAAAASUVORK5CYII=';
        $content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-src="${2}"${3}>', $placeholder ), $content );
        // $content = preg_replace( '#<img([^>]+?)class=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', '<img${1}class="${2} lazyload"${3}>', $content );
        $script = '<script type="text/javascript"> require(["jquery", "magiccart/lazyload", "domReady!"], function($, lazyload){$("img").not(".notlazy").lazyload();});</script>';
        $content = str_ireplace('</body', $script . '</body', $content);
        return $content;
    }

}
