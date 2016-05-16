<?php
/**
 * Created by PhpStorm.
 * User: bodrik
 * Date: 16.05.16
 * Time: 0:37
 */
namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;

class Person extends ActiveRecord implements IdentityInterface
{

    public static $id;
    public static $login;
    public static $fullName;
    public static $password;
    public static $accessType;
    public static $authKey;

    public static $user;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $users = self::find()->where(['id' => $id])->all();
        foreach ($users as $user) {
            static::$user = $user;
            static::$id = $user['id'];
            static::$login = $user['login'];
            static::$fullName = $user['fio'];
            static::$password = $user['password'];
            static::$accessType = $user['access_type'];
            static::$authKey = md5($user['password'].$user['fio'].date('Y-m-d H:i:s'));
            break;
        }
        return isset(self::$user) ? new static(self::$user) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        var_dump(static::$fullName); die;
/*        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $users = self::find()->all();
        foreach ($users as $user) {
            if ($user['login'] == $username) {
                static::$user = $user;
                static::$id = $user['id'];
                static::$login = $user['login'];
                static::$fullName = $user['fio'];
                static::$password = $user['password'];
                static::$accessType = $user['access_type'];
                static::$authKey = md5($user['password'].$user['fio'].date('Y-m-d H:i:s'));
                return new static($user);
            }
        }
/*        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return static::$id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return static::$authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return static::$authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
/*        var_dump(static::$password);
        var_dump(hash_hmac('md5', $password, Yii::$app->request->passwordSalt)); die;*/
        return static::$password === hash_hmac('md5', $password, Yii::$app->request->passwordSalt);
    }
}