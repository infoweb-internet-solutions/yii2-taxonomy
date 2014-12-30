<?php if (Yii::$app->getSession()->hasFlash('term')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('term') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('term-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('term-error') ?></p>
</div>
<?php endif; ?>