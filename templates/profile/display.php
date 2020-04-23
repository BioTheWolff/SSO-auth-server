<?php $this->layout('template', ['title' => 'Profile']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Login cplumn -->
    <div class="column col-6 profile">

        <h2 class="text-center title">Profile</h2>

        <table class="table headless">
            <tbody>
                <tr>
                    <td>Username :</td>
                    <td><?= $this->e($username) ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Email :</td>
                    <td><?= $this->e($email) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <a href="/profile/edit" class="btn">Edit profile</a>
        <a href="/profile/password" class="btn">Change password</a>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>