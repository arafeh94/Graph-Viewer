<?php

use \yii\bootstrap4\Html;
use \rmrevin\yii\fontawesome\FAS;

/**
 * Created by PhpStorm.
 * User: Arafeh
 * Date: 3/23/2019
 * Time: 6:18 AM
 *
 * @var \app\models\Project[] $projects
 * @var int $focus
 */
?>


<div id="accordion">
    <?php foreach ($projects as $project): ?>
        <div class="card" style="margin: 8px">
            <div class="card-header" id="project[<?= $project->id ?>]">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed font-weight-bold" data-toggle="collapse"
                            data-target="#collapseProject<?= $project->id ?>"
                            aria-expanded="<?= $focus == $project->id ? 'true' : 'false' ?>"
                            aria-controls="collapseProject<?= $project->id ?>">
                        <?= Html::tag('text', $project->title, ['style' => 'margin-right:8px']) ?>
                        <?= \yii\bootstrap4\Html::a(FAS::icon('pen-fancy', ['style' => 'color:#6c757d']), ['project/update', 'id' => $project->id], ['class' => 'float-right']) ?>
                    </button>
                </h5>
            </div>
            <div id="collapseProject<?= $project->id ?>" class="collapse <?= $focus == $project->id ? 'show' : '' ?>"
                 aria-labelledby="headingProject[<?= $project->id ?>]" data-parent="#accordion">
                <div class="card-body">
                    <div class="card" style="margin-bottom: 12px">
                        <div class="card-header">Description</div>
                        <div class="card-body">
                            <div>
                                <?= $project->description ?>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-bottom: 12px">
                        <div class="card-header">Graphs</div>
                        <div class="card-body">
                            <div class="graphs card-columns">
                                <?php foreach ($project->graphs as $graph): ?>
                                    <div id="graph<?= $graph->id ?>">
                                        <div class="card">
                                            <div class="card-body">
                                                <?= Html::a($graph->title, ['graph/view', 'graphId' => $graph->id], []) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="add-graph card">
                                    <div class="card-body">
                                        <?= Html::a('Add Graph', ['graph/create', 'projectId' => $project->id], []) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-columns">
                        <div class="card">
                            <div class="card-header">Authors</div>
                            <div class="card-body">
                                <?php if (empty($project->authors)): ?>
                                    <?= "Not Available" ?>
                                <?php else: ?>
                                    <?php foreach ($project->authors as $author): ?>
                                        <?= Html::a($author->name, $author->address, ['target' => "_blank"]) ?>,
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">Publishers</div>
                            <div class="card-body">
                                <?php if (empty($project->publishers_url)): ?>
                                    <?= "Not Available" ?>
                                <?php else: ?>
                                    <?php foreach (explode(',', $project->publishers_url) as $url): ?>
                                        <?= Html::a($url, $url, ['target' => "_blank"]) ?>,
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">Download Paper</div>
                            <div class="card-body">
                                <?= Html::a('Download Link', $project->download_url, ['target' => "_blank"]) ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($project->youtube_id): ?>
                        <div id="video-expandable<?= $project->id ?>">
                            <div class="card" style="margin: 8px">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed font-weight-bold" data-toggle="collapse"
                                                data-target="#collapseObject<?= $project->id ?>"
                                                aria-expanded="false"
                                                aria-controls="collapseObject<?= $project->id ?>">
                                            Video Preview
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseObject<?= $project->id ?>" class="collapse"
                                     aria-labelledby="headingProject"
                                     data-parent="#video-expandable<?= $project->id ?>">
                                    <div style="margin: auto" class="videoWrapper">
                                        <?= \tuyakhov\youtube\EmbedWidget::widget([
                                            'code' => "$project->youtube_id",
                                            'playerParameters' => [
                                                'controls' => 2
                                            ],
                                            'iframeOptions' => [
                                                'width' => '600',
                                                'height' => '450'
                                            ]
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div style='margin-top:8px'>
    <?= \yii\bootstrap\Html::a('Create Project', ['project/create'], [
        'class' => 'btn btn-success',
    ]) ?>
    <div class="btn text-info">
        Click on project title to expand it
    </div>
</div>

<style>
    .videoWrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        padding-top: 25px;
        height: 0;
    }

    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
