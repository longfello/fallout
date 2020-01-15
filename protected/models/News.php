<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $title_news
 * @property string $news
 * @property string $date
 * @property string $img
 * @property string $section
 */
class News extends MLModel
{
  public $img;
  public $MLFields = array(
    'title',
    'description',
    'title_news',
    'news'
  );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title_news, news, img, section', 'required'),
            array('title, description, section', 'length', 'max'=>255),
            array('img', 'length', 'max'=>150),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, description, title_news, news, date, img, section', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
          'id' => t::get('ID'),
          'title' => t::get('Title'),
          'description' => t::get('Description'),
          'title_news' => t::get('Title News'),
          'news' => t::get('News'),
          'date' => t::get('Date'),
          'img' => t::get('Img'),
          'section' => t::get('Section'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('title_news',$this->title_news,true);
        $criteria->compare('news',$this->news,true);
        $criteria->compare('date',$this->date,true);
        $criteria->compare('img',$this->img,true);
        $criteria->compare('section',$this->section,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return News the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function scopes()
    {
        return array(
            'news' => array(
                'condition' => 'section="news"',
            ),
            'games' => array(
                'condition' => 'section="games"',
            ),
            'article' => array(
                'condition' => 'section="article"'
            )
        );
    }

  public function getImg(){
    if($this->img) {
      return '/images/news/'.$this->img;
    } else {
      return '/images/slide_01.jpg';
    }
  }
}