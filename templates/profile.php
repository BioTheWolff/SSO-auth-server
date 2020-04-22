<?php $this->layout('template', ['title' => 'Profile']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Login cplumn -->
    <div class="column col-9 profile">

        <?= $this->e($username) ?>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>