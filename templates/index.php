<?php use App\Session;

$this->layout('template', ['title' => 'Index']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-4"></div>


    <!-- Index column -->
    <div class="column col-4 login">

        <h3>Welcome, <?= Session::get_user_value('username') ?></h3>

        <p>
            You are now here because you successfully connected into the CAS system. The website you were trying to
            access did not provide any redirection, so you are now seeing this.
            <br>
            If you wish to disconnect, <a href="<?= USER_LOGOUT ?>">click here</a>.
        </p>

    </div>


    <!-- Right third left empty -->
    <div class="column col-4"></div>
</div>