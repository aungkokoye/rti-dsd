<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\Comment;
use app\models\Ticket;
use app\models\TicketSearch;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        $ticket = $this->findModel($id);

        $commentModel = new Comment();
        $commentModel->ticket_id = $ticket->id;

        return $this->render('view', [
            'model' => $ticket,
            'commentModel' => $commentModel,
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception|\yii\base\Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Ticket();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->attachmentFiles = UploadedFile::getInstances($model, 'attachmentFiles');

            if ($model->save()) {
                $this->saveAttachments($model);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Save attachments for a ticket
     * @param Ticket $model
     * @throws Exception
     * @throws \yii\base\Exception
     */
    protected function saveAttachments(Ticket $model): void
    {
        if (!empty($model->attachmentFiles)) {
            $uploadPath = Yii::getAlias('@webroot/uploads/tickets/' . $model->id);
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($model->attachmentFiles as $file) {
                $fileName = time() . '_' . Yii::$app->security->generateRandomString(8) . '.' . $file->extension;
                $filePath = $uploadPath . '/' . $fileName;

                if ($file->saveAs($filePath)) {
                    $attachment = new Attachment();
                    $attachment->model_id = $model->id;
                    $attachment->model_type = Ticket::class;
                    $attachment->file_name = $file->baseName . '.' . $file->extension;
                    $attachment->file_path = '/uploads/tickets/' . $model->id . '/' . $fileName;
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

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionAssigneeUpdate($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax && $request->isPost) {

            $model = $this->findModel($id);

            $model->assignee_id = $request->post('assignee_id');
            $model->status_id   = $request->post('status_id');

            if ($model->save(false, ['assignee_id', 'status_id'])) {
                return $this->asJson([
                    'success' => true,
                ]);
            }

            return $this->asJson([
                'success' => false,
                'errors' => $model->getErrors(),
            ]);
        }

        throw new \yii\web\BadRequestHttpException('Invalid request');
    }



    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Ticket
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
