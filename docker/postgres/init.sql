-- Runs only when the Postgres volume is first created.
-- The app's own migration also enables pgvector, this keeps the test database ready.
CREATE DATABASE conditiongame_test OWNER conditiongame;

\connect conditiongame
CREATE EXTENSION IF NOT EXISTS vector;

\connect conditiongame_test
CREATE EXTENSION IF NOT EXISTS vector;
