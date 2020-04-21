<?php $this->layout('template', ['title' => $this->e($title)]) ?>

<div class="columns">

    <div class="column col-4"></div>

    <div class="column col-4 http-error text-center">
        <h2 class="text-error"><?= $this->e($title) ?></h2>

        <p class="text-warning">
            If you believe this is an error, please contact your admnistrator.
        </p>
    </div>

    <div class="column col-4"></div>


</div>