<?php
/**
 *
 * @package     basic
 *
 * @subpackage  controllers
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

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;
use app\models\User;

/**
 * Class that controlls
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('site/index');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function init()
    {
        parent::init();
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Registers a user
     *
     * @return string
     */
    public function actionRegister()
    {
        $model = new RegistrationForm();

        if ($this->request->isPost) {
            $model->username = Yii::$app->request->post()['username'];
            $model->password = Yii::$app->request->post()['password'];
            if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
                Yii::$app->session->setFlash('success', 'Registration successful. Please log in.');
                return $this->redirect(['login']);
            }
        }
        return $this->render('register', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
