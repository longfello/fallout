<?php

  class HomePager extends CLinkPager {
    const CSS_HIDDEN_PAGE='hidden';
    const CSS_SELECTED_PAGE='current';

    public $header = '<div class="pagination"><ul class="row">';
    public $footer = '</ul></div>';

    public $previousPageCssClass = 'prev-page';
    public $nextPageCssClass = 'next-page';
    public $selectedPageCssClass = 'active';
    public $maxButtonCount = 6;

    public function run(){
      //
      // here we call our createPageButtons
      //
      $buttons=$this->createPageButtons();
      //
      // if there is nothing to display return
      if(empty($buttons))
        return;
      //
      // display the buttons
      //
      echo $this->header; // if any
      echo implode("&nbsp;",$buttons);
      echo $this->footer;  // if any
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string the text label for the button
     * @param integer the page number
     * @param string the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
     * @param boolean whether this page button is visible
     * @param boolean whether this page button is selected
     * @return string the generated button
     */
    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons()
    {
      if(($pageCount=$this->getPageCount())<=1)
        return array();

      list($beginPage,$endPage)=$this->getPageRange();
      $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
      $buttons=array();

      // prev page
      if(($page=$currentPage-1)<0)
        $page=0;
      $buttons[]=$this->createPageButton("←",$page,$this->previousPageCssClass,$currentPage<=0,false);

      // first page
      $buttons[]=$this->createPageButton(1,0,$this->firstPageCssClass,false,$currentPage == 0);

      // dots
      $buttons[]=$this->createPageButton('...',false,$this->firstPageCssClass,$beginPage<=1,false);

      // internal pages
      for($i=$beginPage;$i<=$endPage;++$i)
        $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);

      // dots
      $buttons[]=$this->createPageButton('...',false,$this->firstPageCssClass,$endPage>=($pageCount-2),false);

      // last page
      $buttons[]=$this->createPageButton($pageCount,$pageCount-1,$this->lastPageCssClass,false,($pageCount-1)==$currentPage);

      // next page
      if(($page=$currentPage+1)>=$pageCount-1)
        $page=$pageCount-1;
      $buttons[]=$this->createPageButton('→',$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

      return $buttons;
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
      if($hidden || $selected)
        $class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);

      if ($page === false) {
        return '<li class="'.$class.'"><span>'.$label.'</span></li>';
      } else {
        return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page)).'</li>';
      }
    }

    /**
     * Creates the URL suitable for pagination.
     * This method is mainly called by pagers when creating URLs used to
     * perform pagination. The default implementation is to call
     * the controller's createUrl method with the page information.
     * @param CController the controller that will create the actual URL
     * @param integer the page that the URL should point to. This is a zero-based index.
     * @return string the created URL
     */
    public function createPageUrl($page)
    {
      // HERE I USE POST AS I DO AJAX CALLS VIA POST NOT GET AS IT IS BY
      // DEFAULT ON YII
      if ($page === false) return '#';
      $params=$this->getPages()->params===null ? $_POST : $this->getPages()->params;
      if($page>0) // page 0 is the default
        $params[$this->getPages()->pageVar]=$page+1;
      else
        unset($params[$this->getPages()->pageVar]);
      return Yii::app()->controller->createUrl($this->getPages()->route,$params);
    }

    /**
     * @return array the begin and end pages that need to be displayed.
     */
    protected function getPageRange()
    {
      $currentPage=$this->getCurrentPage();
      $pageCount=$this->getPageCount();

      $beginPage=max(1, $currentPage-(int)($this->maxButtonCount/2));
      if(($endPage=$beginPage+$this->maxButtonCount-1)>=$pageCount)
      {
        $endPage=$pageCount-2;
        $beginPage=max(1,$endPage-$this->maxButtonCount+1);
      }
      return array($beginPage,$endPage);
    }

}