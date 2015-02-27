<?php if (Yii::$app->getSession()->hasFlash('taxonomy')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('taxonomy') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('taxonomy-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('taxonomy-error') ?></p>
</div>
<?php endif; ?>