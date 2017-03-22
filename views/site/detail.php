<?php

/**
 * Detail car page
 *
 * @var $model \app\models\Car
 */
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\widgets\CarsBlock\CarsBlock;

$this->title = 'Аренда автомобиля ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => $model->company->name, 'url' => Url::to(['site/company', 'value' => $model->company->code])];
$this->params['breadcrumbs'][] = $model->fullName;

?>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
            </div>
        </div>
    </section>

    <h1 class="text-center"><?= $model->fullName; ?></h1>
    <section class="detail-car-info">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-offset-2 detail-car-img">
                    <img src="<?= $model->img; ?>" class="img-responsive img-thumbnail">
                </div>

                <div class="col-xs-12 col-md-4">
                    <table class="table table-striped">
                        <tr>
                            <td><?= $model->getAttributeLabel('type') ?></td>
                            <td><?= $model->type; ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('color') ?></td>
                            <td><?= $model->color; ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('engine') ?></td>
                            <td><?= $model->engine; ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('transmission') ?></td>
                            <td><?= $model->transmission; ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('privod') ?></td>
                            <td><?= $model->privod; ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('speed') ?></td>
                            <td>До <?= $model->speed; ?> км/ч</td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('price') ?></td>
                            <td>От <?= $model->price; ?> руб.</td>
                        </tr>
                    </table>
                    <button class="btn btn-primary btn-lg">Заказать</button>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?= $model->description; ?>
                </div>
            </div>
        </div>
    </section>

<?= CarsBlock::widget(['option' => 'type', 'value' => $model->type, 'except' => $model->id]); ?>