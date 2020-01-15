<?php

    class Window
    {
        public static function highlight($text)
        {
            $html = '<div class="highlightUI ui-state-highlight ui-corner-all" style="padding: 10px; margin: 10px 0;">';
            $html .= '<span class="ui-icon ui-icon-info" style="float: left; margin-right: 5px; padding: 0;"></span>';
            $html .= $text;
            $html .= '</div>';
            
            return $html;
        }
        
        public static function error($text)
        {
            $html = '<div class="errorUI ui-state-error ui-corner-all" style="padding: 10px; margin:10px 0;">';
            $html .= '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 5px; padding: 0;"></span>';
            $html .= $text;
            $html .= '</div>';
            
            return $html;
        }     
    }
    
    
