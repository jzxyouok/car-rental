<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property integer $add_date
 *
 * @property Order[] $orders
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => 'add_date'
                ]
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'fullName' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'add_date' => 'Дата регистрации'
        ];
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->surname . ' ' . $this->patronymic;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /*
    public function getUserOrders()
    {
        return $this->hasMany(UserOrder::className(), ['user_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('user_order', ['user_id' => 'id']);
    }*/

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken не реализован');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        throw new NotSupportedException('getAuthKey не реализован');
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('validateAuthKey не реализован');
    }
}
