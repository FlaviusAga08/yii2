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
use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\Newuser;
use yii\base\Security;

/**
 * class that is for registration
 */
class RegistrationForm extends ActiveRecord
{
    public $username;
    public $password;

    /**
     * Set what to request for registration
     * @return array
     */
    public function rules()
    {
        return [
            [['username',  'password'], 'required'],
        ];
    }

    /**
     * Here the user is registered
     * @return $user
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $security = Yii::$app->security;

            $hash = $security->generatePasswordHash($this->password);

            $user->username = $this->username;
            $user->password = $hash;

            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
