<?php $this->layout('template', ['title' => 'Edit profile']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Profile edition column -->
    <div class="column col-6 profile">

        
        <a href="<?= USER_PROFILE ?>" class="back"><i class="icon icon-2x icon-back"></i></a>
        <h2 class="text-center title">Profile edition</h2>

        <form class="form-group" method="POST">

            <!-- Email -->
            <label class="form-label" for="email">E-mail</label>
            <input class="form-input" type="email" name="email" id="email"
                placeholder="Email" required=""
                value="<?= $this->e($email) ?>">

            <!-- Username -->
            <label class="form-label" for="username">Username</label>
            <input class="form-input" type="text" name="username" id="username"
                placeholder="Username" required=""
                value="<?= $this->e($username) ?>">

            <!-- Username -->
            <label class="form-label" for="confirm">Password</label>
            <input class="form-input" type="password" name="pass" id="confirm"
                placeholder="Type password to confirm changes" required="">

            <span class="text-error d-block <?= $this->e($error) != '' ? 'display-error' : '' ?>">
                <?= $this->e($error) ?>
            </span>

            <input class="btn btn-primary" type="submit" value="Update profile">

        </form>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>