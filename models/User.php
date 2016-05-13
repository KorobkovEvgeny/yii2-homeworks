<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $subname
 * @property string $password
 * @property string $salt
 * @property string $access_token
 * @property string $create_date
 
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    const MIN_PASS_LENGTH = 6;
    const MAX_USER_NAME = 128;
    const MAX_LOGIN = 45;
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'subname', 'password'], 'required'],
            [['password'], 'string', 'min'=>  self::MIN_PASS_LENGTH],
            [['username'], 'string', 'max' => self::MAX_USER_NAME],
            [['name', 'subname'], 'string', 'max' => self::MAX_LOGIN],
            [['access_token','username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => _('ID'),
            'username' => _('Логин'),
            'name' => _('Имя'),
            'subname' => _('Фамилия'),
            'password' => _('Пароль'),
            'salt' => _('Соль'),
            'access_token' => _('Ключ Авторизации'),
        ];
    }
    /*Реализация IdentityInterface
     * Запрос в БД для поиска конкретного access_token
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token'=>$token]);
    }
    //Получение id пользователя
    public function getId() {
        return $this->id;
    }
    //Получение acess_token
    public function getAuthKey() {
        return $this->access_token;
    }
    //Поиск записи о пользователе по id
    public static function findIdentity($id) {
        return static::findOne(['id'=>$id]);
    }
    //валидация access_token
    public function validateAuthKey($authKey) {
        return $this->getAuthKey()===$authKey;
    }
    /*Реализация IdentityInterface завершена*/
    
    //Генерация соли
    public function saltGenerator()
    {
        return hash('sha512',  uniqid('salt_',true));
    }
    //Возвращение зашифрованного пароля
    public function passWithSalt ($password, $salt)
    {
        return hash("sha512", $password . $salt);
    }
    //Действия выполняемые перед добавление нового пользователя
    public function beforeSave ($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->getIsNewRecord() && !empty($this->password))
            {
                $this->salt = $this->saltGenerator();
            }
            if (!empty($this->password))
            {
                $this->password = $this->passWithSalt($this->password, $this->salt);
            }
            else
            {
                unset($this->password);
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    //Поиск пользователя по имени
    public static function findByUsername ($username)
    {
        return static::findOne(['username' => $username]);
    }
    //Валидация пароля
    public function validatePassword ($password)
    {
        return $this->password === $this->passWithSalt($password, $this->salt);
    }
    //добавление соли к паролю
    public function setPassword ($password)
    {
        $this->password = $this->passWithSalt($password, $this->saltGenerator());
    }
    // Генерация access_token
    public function generateAuthKey ()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Return shared dates
     *
     * @return \yii\db\ActiveQuery
     */

    public function getSharedDates()
    {
        return $this->hasMany(Access::className(), ['user_guest' => 'id']);
    }

    /**
     * Return all events with shared dates
     *
     * @return array
     */
    public function getSharedEvents()
    {
        $dates = $this->sharedDates;

        $objects = array();

        for($i = 0; $i < count($dates); $i++) {
            $objects[] = Calendar::find()
                ->withDate($dates[$i]['date'])
                ->withCreator($dates[$i]['user_owner'])
                ->all();
        }

        return $objects;
    }
}
