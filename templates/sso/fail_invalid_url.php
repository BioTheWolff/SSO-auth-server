<?php $this->layout('template', ['title' => 'Auth error: Invalid URL']) ?>

<div class="columns">

    <div class="column col-4"></div>

    <div class="column col-4 http-error text-center">
        <h2 class="text-error">Failed redirection: Wrongly formed URL</h2>

        <p class="text-warning">
            The redirection failed because the redirect URL was not constructed the right way.<br>
            Return URLs must be of form <code>&lthttp|https&gt://&ltdomain name&gt/[optional path]</code><br>
            Please contact the administrator of the site that redirected you here.
        </p>
    </div>

    <div class="column col-4"></div>


</div>