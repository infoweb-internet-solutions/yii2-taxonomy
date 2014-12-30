<?php

namespace infoweb\catalogue\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\base\Exception;

use infoweb\catalogue\models\Category;
use infoweb\catalogue\models\search\CategorySearch;
use infoweb\catalogue\models\Lang;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'position' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        // @todo Get current taxonomy id
        $root = 0;

        return $this->render('index', [
            'tree' => Category::find()->sortableTree($root),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Get the languages
        $languages = Yii::$app->params['languages'];

        // @todo Get the root
        $root = 1;

        // Load the model
        $model = new Category(['active' => 1]);

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

                    $parent = Category::findOne($post['Category']['parent_id']);

                    // Save the main model
                    if (!$model->load($post) || !$model->appendTo($parent)) {
                        throw new Exception(Yii::t('ecommerce', 'Failed to save the node'));
                    }

                    // Save the translations
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        // Set the translation language and attributes
                        $model->language        = $languageId;
                        $model->name            = $data['name'];

                        if (!$model->saveTranslation()) {
                            throw new Exception(Yii::t('ecommerce', 'Failed to save the translation'));
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('catalogue', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));

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
            Yii::$app->getSession()->setFlash('catalogue-error', $e->getMessage());
        }

        return $this->render('create', [
            'model' => $model,
            'terms' => $model::find()->dropDownList($root),
            'parent_id' => $root,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $languages = Yii::$app->params['languages'];
        $model = Category::findOne($id);

        // @todo Get the root
        $root = 1;

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

                    $parent = Category::findOne($post['Category']['parent_id']);

                    $previous = $model->prev()->one();

                    // Save the main model
                    if (!$model->load($post)) {
                        throw new Exception(Yii::t('ecommerce', 'Failed to load the node'));
                    }

                    // If there is a new parent, move as last
                    if ($parent->id <> $model->parent()->one()->id) {

                        if (!$model->moveAsLast($parent)) {
                            throw new Exception(Yii::t('ecommerce', 'Failed to update the node'));
                        }

                    // If there is a ancestor sibling, move after the sibling
                    } elseif (isset($previous)) {

                        if (!$model->moveAfter($previous)) {
                            throw new Exception(Yii::t('ecommerce', 'Failed to update the node'));
                        }

                    // Or else, move as first
                    } else {

                        if (!$model->moveAsFirst($parent)) {
                            throw new Exception(Yii::t('ecommerce', 'Failed to update the node'));
                        }

                    }

                    // Save the translations
                    foreach ($languages as $languageId => $languageName) {

                        $data = $post['Lang'][$languageId];

                        // Set the translation language and attributes
                        $model->language    = $languageId;
                        $model->name        = $data['name'];

                        if (!$model->saveTranslation()) {
                            throw new Exception(Yii::t('ecommerce', 'Failed to save the translation'));
                        }
                    }

                    $transaction->commit();

                    // Switch back to the main language
                    $model->language = Yii::$app->language;

                    // Set flash message
                    Yii::$app->getSession()->setFlash('catalogue', Yii::t('app', '{item} has been updated', ['item' => $model->name]));

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
            Yii::$app->getSession()->setFlash('catalogue-error', $e->getMessage());
        }

        return $this->render('update', [
            'model' => $model,
            'terms' => $model::find()->dropDownList($root),
            'parent_id' => $model->parent()->one()->id,
        ]);
    }

    /**
     * Deletes an existing Category model and it's descendants
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $model->deleteNode();
            $transaction->commit();
        } catch(Exception $e) {

            if (isset($transaction)) {
                $transaction->rollBack();
            }
            // Set flash message
            Yii::$app->getSession()->setFlash('catalogue-error', $e->getMessage());
        }

        // Set flash message
        $model->language = Yii::$app->language;
        Yii::$app->getSession()->setFlash('catalogue', Yii::t('ecommerce', '{item} and descendants have been deleted', ['item' => $model->name]));

        return $this->redirect(['index']);
    }


    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Saves the new sort order
     * @return mixed
     */
    public function actionSort()
    {
        try {

            $post = Yii::$app->request->post();

            //if(!isset($post['ids']))
                //throw new Exception(Yii::t('infoweb/menu', 'Invalid items'));

            // The category you dragged to change it's position
            $category = Category::findOne($post['category']);

            // The parent or target of the category after your dragged it
            $parent = Category::findOne($post['parent']);

            // Direction: move the category before, after or first (=new list) the new parent
            if ($post['direction'] == 'before') {
                $category->moveBefore($parent);
            }
            elseif ($post['direction'] == 'first') {
                $category->moveAsFirst($parent);
            } else {
                $category->moveAfter($parent);
            }

            $data['status'] = 1;

        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $data['status'] = 0;
        }

        Yii::$app->response->format = 'json';
        return $data;
    }

    /**
     * Set active state
     * @param string $id
     * @return mixed
     */
    public function actionActive()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->active = ($model->active == 1) ? 0 : 1;

        $data['status'] = $model->save();
        $data['active'] = $model->active;

        // Set active status for descendants
        foreach ($model->descendants()->all() as $category) {
            $category->active = $model->active;
            $data['status'] = $category->save();
        }

        Yii::$app->response->format = 'json';
        return $data;
    }
}
