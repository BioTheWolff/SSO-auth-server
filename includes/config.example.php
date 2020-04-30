<?php

// Database
define('DATABASE_SYSTEM', '');
define('DATABASE_HOSTNAME', '');

define('DATABASE_DATABASE', '');
define('DATABASE_TABLE', '');

define('DATABASE_USERNAME', '');
define('DATABASE_PASSWORD', '');


// SSO KEYPAIR
define('PUBLIC_KEY', '');
define('PRIVATE_KEY', '');


// Used in JWTs for the iss (issuer) claim to verify identity
define('AUTH_SERVER_HOSTNAME', '');

// Only brokers in this list will be accepted and get a JWT
define('ACCEPTED_BROKERS', array(
    'example.com',
    'example.org'
));