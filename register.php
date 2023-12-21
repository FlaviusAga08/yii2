<?php
/**
 *
 * @package     basic
 *
 * @subpackage  site
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

use yii\widgets\ActiveForm;
?>

<body>
    <h1>Make a new account. Fill in the form</h1>
    <?php
    ActiveForm::begin([
        'action' => ['site/register'],
        'method' => 'post'
    ]);
    ?>
    
        <label for="email">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="password">Parola:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <button type="submit">
            Register
        </button>

    <?php
    ActiveForm::end();
    ?>
    <?php
    if (!empty($postData)) {
        if ((!empty($postData['username'])) && (!empty($postData['password']))) {
            echo $postData['username'];
            echo $postData['password'];
        }
    }
    ?>

</body>
