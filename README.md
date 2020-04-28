# SSO-auth-server
A PHP-based SSO auth server built using 
- [Spectre.css](https://picturepan2.github.io/spectre/) for design simplification 
- [Route by ThePHPLeague](https://route.thephpleague.com/) for routes and middleware
- [Plates by ThePHPLeague](http://platesphp.com/) for view rendering
- [PHP-JWT by Firebase](https://github.com/firebase/php-jwt) for JWT tokens

# Installation

The easiest way to install this is to clone the repository. (Assuming to be in the root directory in the list under)
1. Run `composer install`
2. Setup the database. As for now, there is only a process described in `install/create_env.sql` (which can be customised and then run, or even run in CLI, that's your choice)
3. Rename the config file (`mv /includes/config.example.php /includes/config.php`), then customise it. For that, you will need a few info:
    - Database information: system (mysql, psql, etc), hostname, database, table, username and finally password.
    - RSA keypair: You will need to generate a keypair (which can easily be done with openssl, by the way). Default is RSA, feel free to go into the `SSOController` to change algorithm
    - SSO server hostname: Basically, the URL your server will be at (i.e. `accounts.example.com`). Used as a verifier for brokers because it is used as an issuer claim in JWTs

### Credits:
- Background image: Image by [Stampf](https://pixabay.com/users/Stampf-1703749/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=3337447) from [Pixabay](https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=3337447), modified with PaintDotNet to be darkened and blurred
- Brand logo: profile picture of [Gray Wolf Games](https://twitter.com/graywolfgames) passed through a sepia filter