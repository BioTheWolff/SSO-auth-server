CREATE USER ssoserver WITH PASSWORD 'pass';

CREATE DATABASE sso OWNER ssoserver;

USE sso;

CREATE TABLE test (
    id          SERIAL PRIMARY KEY,
    username    varchar(40) UNIQUE NOT NULL,
    email       varchar(100) UNIQUE NOT NULL,
    password    varchar(255) NOT NULL,
    admin       boolean DEFAULT false
);