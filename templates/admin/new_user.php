<?php $this->layout('template', ['title' => 'Admin panel']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Login column -->
    <div class="column col-6 profile">

        <h2 class="text-center title">Create a new user</h2>

        <form class="form-group" method="POST">

            <label class="form-label" for="email">E-mail</label>
            <input class="form-input" type="email" name="email" id="email" placeholder="Email" required="">

            <label class="form-label" for="name">Username</label>
            <input class="form-input" type="text" name="username" id="name" placeholder="Email" required="">

            <label class="form-label" for="passwd">Password</label>
            <input class="form-input" type="password" name="pass" id="passwd" placeholder="Password" required="">

            <span class="text-error d-block <?= $this->e($error) != '' ? 'display-error' : '' ?>">
                <?= $this->e($error) ?>
            </span>

            <input class="btn btn-primary" type="submit" value="Create">

        </form>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>