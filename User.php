<?php
/**
 *
 * @package     basic
 *
 * @subpackage  models
 *
 * @author      Aga Flavius David <flavius.aga@gmail.com>
 * @copyright   2019-2023 Aga Flavius David
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    nextcrm
 * @see         https://www.yiiframework.com/doc/guide/2.0/en/structure-controller
 *
 * @since       2023-11-23
 *
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * //@var mixed $id
 * class that defines the
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     *
     */
    private static $_users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            'password' => [['password'], 'string', 'min' => 1, 'max' => 60],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Pass',
        ];
    }

    public function beforeValidate()
    {
        return parent::beforeValidate();
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            $this->generateAccessToken();
            $this->generateAuthKey();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return isset(self::$_users[$id]) ? new static(self::$_users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$_users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    public function generateAccessToken()
    {
        $this->access_Token = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validate password
     * @param string $password Password to validate
     * @return boolean If password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // echo '<pre>';
        // var_dump($this->findByUsername($this->username)->password);
        // echo '</pre>';
        // die;
        if ($this->findByUsername($this->username)->password) {
            return Yii::$app->security->validatePassword($password, $this->findByUsername($this->username)->password);
        } else {
            return false;
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password String from which to generate Hash
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates username
     *
     *@param string $username Username to validate
     *@return boolean if username provided is valid for current user
     */
    public function validateUsername($username)
    {
        
        return $this->username === $username;
    }
}
