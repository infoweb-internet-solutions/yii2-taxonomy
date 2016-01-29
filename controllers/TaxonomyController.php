<?php

namespace infoweb\taxonomy\controllers;

use infoweb\taxonomy\models\Taxonomy;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\base\Exception;

use infoweb\taxonomy\models\Term;
use infoweb\taxonomy\models\Search;
use infoweb\taxonomy\models\Lang;

/**
 * TaxonmoyController implements the CRUD actions for Term model.
 */
class TaxonomyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Term models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject(Search::className());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Term model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $languages = Yii::$app->params['languages'];

        // Load the model with default values
        // @todo Setting root to 0 is not necessary, but returns an error when you don't
        $model = new Term(['root' => 0]);

        $returnOptions = [
            'model' => $model,
        ];

        try {

            if (Yii::$app->request->getIsPost()) {

                $post = Yii::$app->request->post();

                // Ajax request, validate the models
                if (Yii::$app->request->isAjax) {

                    // Populate the model with the POST data
                    $model->load($post);

                    // Create an array of translation models
                    $translationModels = [];

                    foreach ($languages as $languageId => $languageName) {
                        $translationModels[$languageId] = new Lang(['language' => $languageId]);
                    }

                    // Populate the translation models
                    Model::loadMultiple($translationModels, $post);

                    // Validate the model and translation models
                    $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                    // Return validation in JSON format
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return $response;

                    // Normal request, save models
                } else {
                    // Wrap the everything in a database transaction
                    $transaction = Yii::$app->db->beginTransaction();

                    // Save the main model
                    if (!$model->load($post) || !$model->makeRoot()) {
                        return $this->render('create', $returnOptions);
                    }

                    // Save the translations
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        // Set the translation language and attributes
                        $model->language        = $languageId;
                        $model->name            = $data['name'];
                        $model->content         = $data['content'];

                        if (!$model->saveTranslation()) {
                            return $this->render('create', $returnOptions);
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('term', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));

                    // Take appropriate action based on the pushed button
                    if (isset($post['close'])) {
                        return $this->redirect(['index']);
                    } elseif (isset($post['new'])) {
                        return $this->redirect(['create']);
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }
            }

        } catch (Exception $e) {

            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Set flash message
            Yii::$app->getSession()->setFlash('term-error', $e->getMessage());
        }

        return $this->render('create', $returnOptions);
    }

    /**
     * Updates an existing Term model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $languages = Yii::$app->params['languages'];

        // Load the model with default values
        $model = $this->findModel($id);

        $returnOptions = [
            'model' => $model,
        ];

        try {

            if (Yii::$app->request->getIsPost()) {

                $post = Yii::$app->request->post();

                // Ajax request, validate the models
                if (Yii::$app->request->isAjax) {

                    // Populate the model with the POST data
                    $model->load($post);

                    // Create an array of translation models
                    $translationModels = [];

                    foreach ($languages as $languageId => $languageName) {
                        $translationModels[$languageId] = $model->getTranslation($languageId);
                    }

                    // Populate the translation models
                    Model::loadMultiple($translationModels, $post);

                    // Validate the model and translation models
                    $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));

                    // Return validation in JSON format
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;

                    // Normal request, save models
                } else {
                    // Wrap the everything in a database transaction
                    $transaction = Yii::$app->db->beginTransaction();

                    // Save the main model
                    if (!$model->load($post) || !$model->save()) {
                        return $this->render('update', $returnOptions);
                    }

                    // Save the translations
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        // Set the translation language and attributes
                        $model->language        = $languageId;
                        $model->name            = $data['name'];
                        $model->content         = $data['content'];

                        if (!$model->saveTranslation()) {
                            return $this->render('update', $returnOptions);
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('term', Yii::t('app', '{item} has been updated', ['item' => $model->name]));

                    // Take appropriate action based on the pushed button
                    if (isset($post['close'])) {
                        return $this->redirect(['index']);
                    } elseif (isset($post['new'])) {
                        return $this->redirect(['create']);
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }
            }
        } catch(Exception $e) {

            if (isset($transaction)) {
                $transaction->rollBack();
            }

            // Set flash message
            Yii::$app->getSession()->setFlash('term-error', $e->getMessage());
        }

        return $this->render('update', $returnOptions);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            $transaction = Yii::$app->db->beginTransaction();
            // @todo Remove children
            $model->delete();
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('page-error', $e->getMessage());

            return $this->redirect(['index']);
        }

        // Set flash message
        Yii::$app->getSession()->setFlash('page', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

        return $this->redirect(['index']);
    }

    /**
     * Set active state
     * @return mixed
     */
    public function actionActive()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->active = ($model->active == 1) ? 0 : 1;

        return $model->save();
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}
