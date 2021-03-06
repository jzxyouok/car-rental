<?php

namespace app\models;

use Faker\Provider\cs_CZ\DateTime;
use Yii;
use app\components\UploadFileBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "car".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $category_id
 * @property string $name
 * @property string $slug
 * @property integer $year
 * @property integer $speed
 * @property string $engine
 * @property string $color
 * @property string $transmission
 * @property string $privod
 * @property string $description
 * @property integer $price
 * @property integer $discount_1
 * @property integer $discount_2
 * @property string $img
 * @property integer $up_date
 *
 * @property Company $company
 * @property UploadFile $file
 * @property Order[] $orders
 */
class Car extends \yii\db\ActiveRecord
{
    public $file;
    private $_days; // How many days

    /**
     * Update up_date column when save and update model
     */
    public function behaviors()
    {
        return [
            [
                'class' => UploadFileBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_VALIDATE => ['img'],
                ],
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['up_date'],
                    self::EVENT_BEFORE_UPDATE => ['up_date']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'category_id', 'year', 'speed', 'price', 'discount_1', 'discount_2'], 'integer'],
            [['name', 'slug', 'year', 'speed', 'engine', 'color', 'transmission', 'privod', 'price'], 'required'],
            [['name', 'slug'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 2000],
            [['engine', 'color', 'transmission', 'privod'], 'string', 'max' => 25],
            ['file', 'file', 'extensions' => ['jpg', 'png', 'gif'], 'maxSize' => 1024 * 1024 * 5],
            [['slug'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']], // TODO: Посмотреть документацию
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Марка',
            'companyName' => 'Марка',
            'name' => 'Название',
            'slug' => 'Символьный код',
            'category_id' => 'Категория',
            'categoryName' => 'Категория',
            'year' => 'Год выпуска',
            'speed' => 'Скорость',
            'engine' => 'Двигатель',
            'color' => 'Цвет',
            'transmission' => 'Transmission',
            'privod' => 'Привод',
            'description' => 'Описание',
            'price' => 'Цена',
            'discount_1' => 'Скидка от ' . Yii::$app->params['discount_1'] . ' дней',
            'discount_2' => 'Скидка от ' . Yii::$app->params['discount_2'] . ' дней',
            'file' => 'Изображение',
            'up_date' => 'Изменение'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['car_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->company->name . ' ' . $this->name;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company->name;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category->name;
    }

    public function getDiscount($start, $end)
    {
        $days = $this->getDays($start, $end);

        if ($this->discount_1 && $days >= Yii::$app->params['discount_1']) {
            if ($this->discount_2 && $days >= Yii::$app->params['discount_2']) {
                return $this->discount_2;
            }

            return $this->discount_1;
        }

        return 0;
    }

    public function getAmount($discount)
    {
        if ($discount == 0) {
            return $this->price;
        } else {
            return $this->price - ($discount / 100 * $this->price);
        }
    }

    public function getMinPrice()
    {
        // For maximum discount
        $days = Yii::$app->params['discount_2'] + Yii::$app->params['discount_1'];

        return $this->getAmount($days);
    }

    private function getDays($start, $end)
    {
        if (is_int($start) && is_int($end)) {
            $start = date('d.m.Y', $start);
            $end = date('d.m.Y', $end);
        }

        $start = new \DateTime($start);
        $end = new \DateTime($end);

        return $this->_days = $start->diff($end)->d;
    }
}
