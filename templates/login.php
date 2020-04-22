<?php $this->layout('template', ['title' => 'Login']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-4"></div>


    <!-- Login cplumn -->
    <div class="column col-4 login">

        <form class="form-group" method="POST">

            <label class="form-label" for="email">E-mail or Username</label>
            <input class="form-input" type="text" name="email" id="email" placeholder="Email" required="">

            <label class="form-label" for="passwd">Password</label>
            <input class="form-input" type="password" name="pass" id="passwd" placeholder="Password" required="">

            <span class="text-error d-block <?= $this->e($error) != '' ? 'display-error' : '' ?>">
                <?= $this->e($error) ?>
            </span>

            <input class="btn btn-primary" type="submit" value="Log in">

        </form>

    </div>


    <!-- Right third left empty -->
    <div class="column col-4"></div>
</div>