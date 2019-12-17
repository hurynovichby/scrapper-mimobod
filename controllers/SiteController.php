<?php

namespace app\controllers;

use app\models\Main;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use PhpQuery\PhpQuery;
use GuzzleHttp\Client; // подключаем Guzzle
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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

    public function actionIndex()
    {

        // сохрнить в бд данные и парсинг
//        $dataFlatAll = [];
//        $dataFlatAll[] = [
//            'name' => 'Однушка',
//            'data' => $this->parceHata('https://www.hata.by/sale-flat/kupit-odnokomnatnuyu-kvartiru__ht/')
//        ];
//        $dataFlatAll[] = [
//            'name' => 'Двушка',
//            'data' => $this->parceHata('https://www.hata.by/sale-flat/kupit-dvuhkomnatnuyu-kvartiru__ht/')
//        ];
//        $dataFlatAll[] = [
//            'name' => 'Трешка',
//            'data' => $this->parceHata('https://www.hata.by/sale-flat/kupit-trehkomnatnuyu-kvartiru__ht/')
//        ];

//        foreach ( $dataFlatAll as $dataFlatAllVal ) {
//
//            if( $dataFlatAllVal['name'] == 'Однушка' ) {
//                $i = 1;
//            }
//
//            if( $dataFlatAllVal['name'] == 'Двушка' ) {
//                $i = 2;
//            }
//
//            if( $dataFlatAllVal['name'] == 'Трешка' ) {
//                $i = 3;
//            }
//
//            foreach ( $dataFlatAllVal['data'] as $dataFlatAllValInfo ) {
//                $main = new Main();
//                $main->price = preg_replace('/[^0-9]/', '', $dataFlatAllValInfo['price']) ;
//                $main->name = $dataFlatAllValInfo['title'];
//                $main->category_id = $i;
//                $main->save();
//            }
//
//        }


        $main = Main::find()
            ->select([
                'main.id',
                'main.name',
                'main.price',
                'category.name as title'
            ])
            ->innerJoin('category', 'category.id = main.category_id')
            ->asArray()
            ->all();

        $listFlatAll = [
            'Однушка' => [
                'sum' => 0,
                'data' => []
            ],
            'Двушка' => [
                'sum' => 0,
                'data' => []
            ],
            'Трешка' => [
                'sum' => 0,
                'data' => []
            ],
        ];

        foreach ( $main as $mainVal ) {
            if( $mainVal['title'] == 'Однушка' ) {
                $listFlatAll['Однушка']['sum'] += $mainVal['price'];
                $listFlatAll['Однушка']['data'][] = $mainVal['price'];
            }
            if( $mainVal['title'] == 'Двушка' ) {
                $listFlatAll['Двушка']['sum'] += $mainVal['price'];
                $listFlatAll['Двушка']['data'][] = $mainVal['price'];
            }
            if( $mainVal['title'] == 'Трешка' ) {
                $listFlatAll['Трешка']['sum'] += $mainVal['price'];
                $listFlatAll['Трешка']['data'][] = $mainVal['price'];
            }
        }

        return $this->render('index', array(
                'main' => $main,
                'listFlatAll' => $listFlatAll
            ));

    }

    private function parceHata($url) {
        // Получаем код
        // example = https://www.hata.by/sale-flat/kupit-odnokomnatnuyu-kvartiru__ht/
        $html = file_get_contents($url);
        $data = [];
        $document = phpQuery::newDocument($html);
        $res = $document->find('.b-catalog-table__item');

        foreach ( $res as $resKey => $resVal ) {
            $data[] = [
                'price' => preg_replace("/ {2,}/"," ",phpQuery::pq($resVal)->find('.price > .value')->text()),
                'title' => preg_replace("/ {2,}/"," ",phpQuery::pq($resVal)->find('.title')->text())
            ];
        }

        return $data;
    }

}
