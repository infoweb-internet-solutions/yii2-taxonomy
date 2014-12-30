<?php if (Yii::$app->getSession()->hasFlash('category')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('category') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('category-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('category-error') ?></p>
</div>
<?php endif; ?>