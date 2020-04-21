<html>
<head>
    <title><?=$this->e($title)?></title>
    <link rel="stylesheet" href="/assets/css/spectre.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<header class="navbar">
  <section class="navbar-section">
    <a href="/" class="btn btn-link">SSO Auth server</a>
  </section>
  <section class="navbar-center">
    <img src="/assets/img/logo.png" alt="LOGO">
  </section>
  <section class="navbar-section">
    <a href="https://github.com/BioTheWolff" class="btn btn-link">Made by Fabien "BioTheWolff" Z.</a>
    <a href="https://github.com/BioTheWolff/SSO-auth-server" class="btn btn-link">GitHub project</a>
  </section>
</header>

<?=$this->section('content')?>

</body>
</html>