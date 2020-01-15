<?php

class RLinkPager extends CLinkPager
{
    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if($hidden || $selected)
            $class .= ' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);

        return '<li class="' . $class . '">' . CHtml::link('[' . $label . ']', $this->createPageUrl($page)) . '</li>';
    }
}