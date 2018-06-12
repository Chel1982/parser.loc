<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $alias
 * @property string $value
 */
class Config extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface
{
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Алиас',
            'value' => 'Значение',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['alias' => 'id'])->value;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return string static
     */
    public static function findByUsername($username)
    {

        if ($username === static::findOne(['alias' => 'name_user'])->value){

            $user = [];
            $user['username'] = static::findOne(['alias' => 'name_user'])->value;
            $user['password'] = static::findOne(['alias' => 'password'])->value;
            $user['authKey'] = static::findOne(['alias' => 'authKey'])->value;
            $user['accessToken'] = static::findOne(['alias' => 'accessToken'])->value;

            return new static($user);

        }
    }

    public function setUsername(){

        $this->username = static::findOne(['alias' => 'name_user'])->value;

    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return static::findOne(['alias' => 'id'])->value;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return static::findOne(['alias' => 'authKey'])->value;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return static::findOne(['alias' => 'authKey'])->value === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return static::findOne(['alias' => 'password'])->value === $password;
    }
}
