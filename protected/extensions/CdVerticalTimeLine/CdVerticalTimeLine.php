<?php

/**
 * Yii wrapper for vertical timeline blueprint, originally created by Mary Lou
 * @see http://tympanus.net/codrops/2013/05/02/vertical-timeline/
 * @see http://tympanus.net/Blueprints/VerticalTimeline/
 */
class CdVerticalTimeLine extends CWidget
{
    /**
     * @var array - list of displayed events
     * Example:
     * array(
     *     array(
     *         'date' => '19.07.2012',
     *         'time' => '19:00',
     *         // event name as text or link
     *         'name' => 'Event name', 
     *         // html options for "h2" tag, containing event name
     *         'nameOptions' => array(),
     *         // alternatively, you can completly replace event title by your own HTML
     *         'nameHtml' => '<h3>Event name</h3><input type="button">',
     *         // any custom HTML allowed here [required]
     *         'description' => 'Event description...',
     *         // html options for "p" tag, event description container
     *         'descriptionOptions' => array(),
     *         // html options for "li" tag event container
     *         'itemOptions' => array(),
     *         // html options for "time" tag, containing date and time
     *         'timeBlockOptions' => array(),
     *         // html options for date span
     *         'dateOptions' => array(),
     *         // html options for time span
     *         'timeOptions' => array(),
     *         // html options for div-event container (inside "li"): 
     *         // use it if you want to set custom block color or opacity
     *         'containerOptions' => array(),
     *         // html options for div containing icon. Use "class" property to set your own icon class
     *         // (useful for Twitter Bootstrap or Font Awesome)
     *         'iconOptions' => array(),
     *         // url of image, that will be used instread of icon 
     *         'iconImage' => 'http://example.com/image.jpg',
     *         // html options for img tag
     *         'iconImageOptions' => array(),
     *         // url for image-icon (if you use image instread of icon, and want to use it as link)
     *         'iconLink' => 'http://example.com',
     *         // html options for CHtml::link function for link above
     *         'iconLinkOptions' => array(),
     *         // you can also use use custom html inside icon div (instread of image)
     *         // this element must have 40x40 px size and "border-radius:50%" to fit in the circle
     *         'iconHtml' => '<b>42</b>',
     *     ),
     *     ...
     * )
     */
    public $events;
    /**
     * @var array - html options for main widget container
     */
    public $containerOptions = array();
    /**
     * @var array - html options for "ul" tag, containing timeline
     */
    public $listOptions      = array();
    /**
     * @var bool - include modernizr library or not
     */
    public $includeModernizr = false;
    
    /**
     * @var string
     */
    protected $assetUrl;
    protected $date;
    
    /**
     * @see CWidget::init()
     */
    public function init()
    {
        $this->assetUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.CdVerticalTimeLine.assets'));
        Yii::app()->clientScript->registerCssFile($this->assetUrl.'/css/component.css');

        if ( $this->includeModernizr )
        {// modernizr library is very popular, and can be already included in your project
            Yii::app()->clientScript->registerScriptFile($this->assetUrl.'/js/modernizr.custom.js');
        }

        if ( isset($this->listOptions['class']) )
        {
            $this->listOptions['class'] = 'timeline '.$this->listOptions['class'];
        }else
        {
            $this->listOptions['class'] = 'timeline';
        }
    }
    
    /**
     * @see CWidget::run()
     */
    public function run()
    {
        $this->render('timeline');
    }
    
    /**
     * Get html content for single event
     * @param array $event - event data
     * @return string
     */
    protected function getEventContent($event)
    {
        $content = '';
        $event = $this->normalizeEventData($event);

        $content = $this->render('event', array('event' => $event));

        /*
        // start event markup
        $content .= CHtml::openTag('li', $event['itemOptions']);
        // date and time block
        $content .= CHtml::openTag('time', $event['timeBlockOptions']);
        $content .= CHtml::tag('span', $event['dateOptions'], $event['date']);
        $content .= CHtml::tag('span', $event['timeOptions'], $event['time']);
        $content .= CHtml::closeTag('time');
        // event icon
        $content .= CHtml::openTag('div', $event['iconOptions']);
        if ( $event['iconImage'] )
        {
            $icon = CHtml::image($event['iconImage'], '', $event['iconImageOptions']);
            if ( $event['iconLink'] )
            {// icon must be a link
                $icon = CHtml::link($icon, $event['iconLink'], $event['iconLinkOptions']);
            }
            $content .= $icon;
        }elseif ( $event['iconHtml'] )
        {
            $content .= $event['iconHtml'];
        }
        $content .= CHtml::closeTag('div');
        // main info container
        $content .= CHtml::openTag('div', $event['containerOptions']);
        if ( $event['name'] )
        {
            $content .= CHtml::tag('h2', $event['nameOptions'], $event['name']);
        }elseif ( $event['nameHtml'] )
        {
            $content .= $event['nameHtml'];
        }
        //$content .= CHtml::tag('p', $event['descriptionOptions'], $event['description']);
        $content .= $event['description'];
        // end of main info container
        $content .= CHtml::closeTag('div');
        // end of event
        $content .= CHtml::closeTag('li');
        */
        return $content;
    }
    
    /**
     * This function prepares event data for output - add missing array keys, set up some default html options
     * @param array $event
     * @return array
     */
    protected function normalizeEventData($event)
    {
        if ( ! is_array($event) )
        {
            throw new CException('Event data must be an array');
        }

        if (isset($event['model'])) {
          $event = $this->fillFromModel($event);
        }

        if ( ! isset($event['description']) )
        {// the only required parameter for event
            throw new CException('Event description required');
        }
        
        $template = array(
            'date' => '',
            'time' => '',
            'name' => '', 
            'nameOptions' => array(),
            'nameHtml'    => '',
            'description' => '',
            'descriptionOptions' => array(),
            'itemOptions' => array(),
            'timeBlockOptions' => array(),
            'dateOptions' => array(),
            'timeOptions' => array(),
            'containerOptions' => array(),
            'iconOptions' => array(),
            'iconImage'   => '',
            'iconImageOptions' => array(),
            'icon'             => '<i class="fa fa-envelope bg-blue"></i>',
            'iconLink'         => '',
            'iconLinkOptions'  => array(),
            'iconHtml'         => '',
            'title'            => '',
            'footer'           => '',
        );
      $event = CMap::mergeArray($template, $event);


        // set up default values
        
        // datetime block
        $event['timeBlockOptions']['datetime'] = $event['date'].' '.$event['time'];
        if ( isset($event['timeBlockOptions']['class']) )
        {
            $event['timeBlockOptions']['class'] = 'cbp_tmtime '.$event['timeBlockOptions']['class'];
        }else
        {
            $event['timeBlockOptions']['class'] = 'cbp_tmtime';
        }
        // event icon
        if ( isset($event['iconOptions']['class']) )
        {
            $event['iconOptions']['class'] = 'cbp_tmicon '.$event['iconOptions']['class'];
        }else
        {
            $event['iconOptions']['class'] = 'cbp_tmicon';
        }
        // event icon (image used)
        if ( isset($event['iconImageOptions']['class']) )
        {
            $event['iconImageOptions']['class'] = 'cbp_tmiconimage '.$event['iconImageOptions']['class'];
        }else
        {
            $event['iconImageOptions']['class'] = 'cbp_tmiconimage';
        }
        // event description container
        if ( isset($event['containerOptions']['class']) )
        {
            $event['containerOptions']['class'] = 'cbp_tmlabel '.$event['containerOptions']['class'];
        }else
        {
            $event['containerOptions']['class'] = 'cbp_tmlabel';
        }
        
        return $event;
    }

  private function fillFromModel($event){
    $model = $event['model'];

    $date = date('d.m.Y', $model->created_at);
    if ($date != $this->date) {
      $this->date = $date;
    } else {
      $date = '';
    }

    $data = json_decode($model->data);

    $event['title'] = isset($data->title)?$data->title:'';
    $event['icon']  = isset($data->icon)?$data->icon:'';
    $event['footer']  = isset($data->footer)?$data->footer:'';
    $event['date']  = $date;
    $event['time']  = date('H:i:s', $model->created_at);
    $event['description'] = $this->render($model->category.'/'.$model->event, array('data' => json_decode($model->data)), true);

    return $event;
  }
}