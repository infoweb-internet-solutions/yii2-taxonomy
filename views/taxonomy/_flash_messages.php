<?php if (Yii::$app->getSession()->hasFlash('taxonomy')): ?>
<div class="alert alert-success">
    <?= Yii::$app->getSession()->getFlash('taxonomy') ?>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('taxonomy-error')): ?>
<div class="alert alert-danger">
    <?= Yii::$app->getSession()->getFlash('taxonomy-error') ?>
</div>
<?php endif; ?>