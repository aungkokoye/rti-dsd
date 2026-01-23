<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\Comment;
use app\models\Ticket;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use Yii;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Displays a single Comment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $ticketId
     * @return string|Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionCreate(int $ticketId): Response|string
    {

        $model = new Comment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->attachmentFiles = UploadedFile::getInstances($model, 'attachmentFiles');
                $model->ticket_id = $ticketId;
                if ($model->save()) {
                    $this->saveAttachments($model);
                    return $this->redirect(['ticket/view', 'id' => $ticketId]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if (\Yii::$app->request->isAjax) {
            return $this->asJson(['success' => true]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Comment
    {
        if (($model = Comment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @throws \yii\base\Exception
     * @throws Exception
     */
    protected function saveAttachments(Comment $model): void
    {
        if (!empty($model->attachmentFiles)) {
            $uploadPath = Yii::getAlias('@webroot/uploads/comments/' . $model->id);
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($model->attachmentFiles as $file) {
                $fileName = time() . '_' . Yii::$app->security->generateRandomString(8) . '.' . $file->extension;
                $filePath = $uploadPath . '/' . $fileName;

                if ($file->saveAs($filePath)) {
                    $attachment = new Attachment();
                    $attachment->model_id = $model->id;
                    $attachment->model_type = Comment::class;
                    $attachment->file_name = $file->baseName . '.' . $file->extension;
                    $attachment->file_path = '/uploads/comments/' . $model->id . '/' . $fileName;
                    $attachment->mimie_type = $file->type;
                    $attachment->file_size = $file->size;
                    // created_by and created_at are handled by behaviors
                    if (!$attachment->save()) {
                        Yii::error('Failed to save attachment: ' . implode(', ', $attachment->getFirstErrors()));
                    }
                }
            }
        }
    }
}
