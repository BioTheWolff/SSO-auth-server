<?php $this->layout('template', ['title' => 'Auth error: No URL']) ?>

<div class="columns">

    <div class="column col-4"></div>

    <div class="column col-4 http-error text-center">
        <h2 class="text-error">Failed redirection: No redirect URL</h2>

        <p class="text-warning">
            The redirection failed because there is no redirect URL passed to the server. If you believe this is an error, please contact your administrator.
        </p>
    </div>

    <div class="column col-4"></div>


</div>