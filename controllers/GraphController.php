<?php

namespace app\controllers;

use app\components\Matlab;
use app\components\Tools;
use app\models\forms\LoginForm;
use app\models\forms\UploadForm;
use app\models\Graph;
use app\models\Project;
use app\models\ProjectQuery;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class GraphController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['update', 'create'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
     * @param $id
     * @return string
     */
    public function actionIndex($id)
    {
        return $this->redirect(['graph/view', 'project' => $id]);
    }

    public function actionView($graphId)
    {
        $result = null;
        $graph = Graph::findOne($graphId);
        $input = $graph->default_input;

        if (Yii::$app->request->post('input', false)) {
            $input = Yii::$app->request->post('input');
            $func = Tools::str_between($graph->mfile, 'm/', '.m');
            $result = Matlab::exec($func, $input)[10];
            $session = Yii::$app->session->id;
            $graphDir = Tools::createFolder("m/graphs/$session");
            $graphPath = "{$graphDir}/{$graph->title}.png";
            rename($result, $graphPath);
            $result = $graphPath;
        }
        return $this->render('view', [
            'graph' => $graph,
            'result' => $result,
            'input' => $input
        ]);
    }

    public function actionCreate($projectId)
    {
        $model = new Graph();
        $model->project_id = $projectId;
        if (Yii::$app->request->post('Graph', false)) {
            $model->load(Yii::$app->request->post());
            $path = UploadForm::save('m');
            $model->mfile = $path;
            if ($model->save()) $this->redirect(['project/view', 'focus' => $model->project_id]);
        }
        return $this->render('modify', [
            'model' => $model
        ]);
    }

    public function actionUpdate($graphId)
    {
        $model = Graph::findOne($graphId);
        $request = Yii::$app->request;
        if ($request->post("Graph", false)) {
            $model->load(Yii::$app->request->post());
            if ($model->isAttributeChanged('mfile')) {
                $path = UploadForm::save('m');
                $model->mfile = $path;
            }
            if ($model->save()) $this->redirect(['project/view', 'focus' => $model->project_id]);
        }
        return $this->render('modify', [
            'model' => $model,
        ]);
    }

}
