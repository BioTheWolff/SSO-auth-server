<?php $this->layout('template', ['title' => 'Change password']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Login cplumn -->
    <div class="column col-6 profile">

        
        <a href="/profile" class="back"><i class="icon icon-2x icon-back"></i></a>
        <h2 class="text-center title">Change password</h2>

        <form class="form-group" method="POST">

            <!-- Email -->
            <label class="form-label" for="old-pass">Current password</label>
            <input class="form-input" type="password" name="current" id="old-pass"
                placeholder="Current password" required="">

            <!-- Username -->
            <label class="form-label" for="new-pass">New password</label>
            <input class="form-input" type="password" name="new" id="new-pass"
                placeholder="New password" required="">

            <!-- Username -->
            <label class="form-label" for="confirm">Confirm password</label>
            <input class="form-input" type="password" name="confirm_new" id="confirm"
                placeholder="Confirm new password" required="">

            <span class="text-error d-block <?= $this->e($error) != '' ? 'display-error' : '' ?>">
                <?= $this->e($error) ?>
            </span>

            <input class="btn btn-primary" type="submit" value="Update password">

        </form>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>