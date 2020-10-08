<?php

/**
 * DATABASE CONFIG
 */
define('DATABASE_SYSTEM', ''); // mysql, pgsql, etc. / WARNING: The create_env.sql is for PostgreSQL only!
define('DATABASE_HOSTNAME', ''); // host:port (i.e. localhost)

define('DATABASE_DATABASE', ''); // database
define('DATABASE_TABLE', ''); // table

define('DATABASE_USERNAME', ''); // username to connect to the database with
define('DATABASE_PASSWORD', ''); // its password


/**
 * KEYPAIR CONFIG
 */
// Usually ECDSA is sufficient
define('PUBLIC_KEY', '');
define('PRIVATE_KEY', '');
define('ALGORITHM', 'ES256');
/**
 * Accepted algorithms:
 * HS256, HS384, HS512,
 * RS256, RS384, RS512,
 * ES256
 */


/**
 * SECURITY CONFIG
 */
// Used in JWTs for the iss (issuer) claim to verify identity
define('AUTH_SERVER_HOSTNAME', 'cas.example.com');

// whether to allow the pubkey route or not (defaults to false because I don't see any "normal" use to it).
define('ALLOW_PUBKEY_ROUTE', false);

// Only brokers in this list will be accepted and get a JWT
define('ACCEPTED_BROKERS', array(
    'example.com',
    'example.org'
));