<?php

namespace app\models;

use Yii;
use app\models\query\AccessQuery;

/**
 * This is the model class for table "access".
 *
 * @property integer $id
 * @property integer $user_owner
 * @property integer $user_guest
 * @property string $date
 *
 * @property User $userOwner
 * @property User $userGuest
 */
class Access extends \yii\db\ActiveRecord
{
    const ACCESS_CREATOR = 1;
    const ACCESS_GUEST = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_owner', 'user_guest'], 'integer'],
            [['user_owner'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_owner' => 'id']],
            [['user_guest'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_guest' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_owner' => Yii::t('app', 'Владелец'),
            'user_guest' => Yii::t('app', 'Гость'),
            'date' => Yii::t('app', 'Дата'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGuest()
    {
        return $this->hasMany(User::className(), ['id' => 'user_guest']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\AccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AccessQuery(get_called_class());
    }
    /**
     * Before save condition allows to set user_owner id to current user id
     * @param bool $insert
     * @return bool
     */
    public function beforeSave ($insert)
    {
        if ($this->getIsNewRecord())
        {
            $this->user_owner = Yii::$app->user->id;
        }
        parent::beforeSave($insert);
        return true;
    }
     /**
     * Check access for Calendar note
     *
     * @param Calendar $model
     * @return bool|int
     */
    public static function checkAccess($model)
    {
        if ($model->creator == Yii::$app->user->id){
            return self::ACCESS_CREATOR;
        }

        $accessCalendar = self::find()
            ->withUserGuest(Yii::$app->user->id)
            ->withSharedDate($model->getDateEventStart())
            ->exists();

        if ($accessCalendar){
            return self::ACCESS_GUEST;
        }

        return false;
    }
}
