<?php $this->layout('template', ['title' => 'Admin panel']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-3"></div>


    <!-- Login column -->
    <div class="column col-6 profile">

        <h2 class="text-center title">Admin panel</h2>

        <?php if($users === false): ?>

            <p class="text-error"><?= ERROR_DATABASE ?></p>
        
        <?php else: ?>
        
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                </tbody>
            </table>

        <?php endif; ?>

    </div>


    <!-- Right third left empty -->
    <div class="column col-3"></div>
</div>