<?php

/**
 * @Author: nguyen
 * @Date:   2021-02-02 20:08:17
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-02-02 20:18:56
 */
namespace Magiccart\Alothemes\Block\System\Config\Form\Field;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Config\Model\Config\CommentInterface;

class ColorComment extends AbstractBlock implements CommentInterface
{
    public function getCommentText($elementValue)
    {
        $maxInputVars =ini_get('max_input_vars');
        $text0 = __('Warning');
        $text1 = __('Parameter');
        $text2 = __('Input parameter limit');
        $text3 = __('Current input parameter');
        $text4 = __('Note: Please increase the parameter <b>max_input_vars</b> to be able to add, edit, delete, and can save the changes.');
        $text5 = '<a href="https://magepow.com/blog/increase-max_input_vars-parameter-in-php/">Blog tutorial to increase the max_input_vars parameter limit</a>';
        $text6 = __('You need to increase the <b>max_input_vars</b> parameter limit to be able to add, edit, delete and save changes.');
        $comment = __('Only use for developer');
        $comment .= <<<script
        <script>
            require(['jquery', 'Magento_Ui/js/modal/alert'], function(jQuery, alert){ 
                jQuery(document).ready(function() {
                    var maxInputVars =  $maxInputVars;
                    var form = jQuery('#config-edit-form');
                    var data = form.serializeArray();
                    var currentVar = data.length;
                    if(currentVar > maxInputVars){
                        jQuery('<div class="count-max_input_vars message message-warning"><table border="1" width="400" height="150"><thead><tr><th colspan="2">$text0</th></tr></thead><tbody><tr><td><span class="label">$text1</span></td><td><b>max_input_vars</b></td></tr><tr><td>$text2</td><td><b style="color: green; font-weight: 400;">$maxInputVars</b></td></tr><tr><td><span class="label">$text3</span></td><td><b class="count" style="color: red; font-weight: 400;">'+currentVar+'</b></td></tr></tbody><tfoot><tr><td colspan="2">$text4<br>$text5</td></tr></tfoot></table></div>').insertBefore('#config-edit-form .entry-edit.form-inline');
                        jQuery('#save').attr('disabled', true);
                        /* alert({ title: 'Notify',content: '$text6',});*/
                    }
                    jQuery('.action-add').on('click', function(e){
                        var dataForm = form.serializeArray();
                        var currentVaradd = dataForm.length;
                        jQuery(document).find('.count-max_input_vars .count').html(currentVaradd);
                        if(currentVaradd > maxInputVars){
                            jQuery('#save').attr('disabled', true);
                           /* alert({ title: 'Notify',content: '$text6',});*/
                        }
                    });
                    jQuery('.action-delete').on('click', function(e){
                        var dataDelete = form.serializeArray();
                        var currentVardelete = dataDelete.length;
                        jQuery('.count-max_input_vars .count').html(currentVardelete);
                        if(currentVardelete < maxInputVars){
                            jQuery('#save').removeAttr('disabled', true);
                        }
                    });
                       
                });
            });   
        </script>
script;
        $comment .= '<style>.count-max_input_vars table tr td{padding:10px}table{margin:auto;}.count-max_input_vars thead th{padding: 10px 0; color: #e22626;}.count-max_input_vars thead{border: 1px solid #e22626;}</style>';
        return $comment;
	}
}