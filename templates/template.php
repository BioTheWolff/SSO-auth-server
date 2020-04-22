<html>
<head>
    <title>Auth server | <?=$this->e($title)?></title>
    <link rel="stylesheet" href="/assets/css/spectre.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/spectre_adjustments.css">
</head>
<body>

<header class="navbar">
  <section class="navbar-section">
    <!-- Main page of SSO auth server -->
    <a href="/" class="btn btn-link">SSO Auth server</a>
  </section>
  <section class="navbar-center">
    <!-- You can put a link to your main website here (say you have accounts.example.com, you could point to example.com here) -->
    <img src="/assets/img/logo.png" alt="LOGO">
  </section>
  <section class="navbar-section">
    <!-- This section can be left empty if you don't plan to put anything relevant here -->
    <a href="https://github.com/BioTheWolff" target="_blank" class="btn btn-link">Made by Fabien "BioTheWolff" Z.</a>
    <a href="https://github.com/BioTheWolff/SSO-auth-server" target="_blank" class="btn btn-link">GitHub project</a>

    <?php if (\App\Session::is_connected()): ?>
      <a href="/logout" class="btn">Log out</a>
    <?php endif; ?>
  </section>
</header>

<div class="container">
<?=$this->section('content')?>
</div>

</body>
</html>