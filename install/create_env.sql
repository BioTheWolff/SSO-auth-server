/* This script is made for PostgreSQL */

CREATE USER ssoserver WITH PASSWORD 'pass';

CREATE DATABASE sso OWNER ssoserver;

\c sso ssoserver localhost;
/* You will then be prompted to type your password */

/* Once connected to the new database, put this in place */
CREATE TABLE accounts (
    id          SERIAL PRIMARY KEY,
    username    varchar(40) UNIQUE NOT NULL,
    email       varchar(100) UNIQUE NOT NULL,
    password    varchar(255) NOT NULL,
    admin       boolean DEFAULT false
);