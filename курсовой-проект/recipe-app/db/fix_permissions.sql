-- Grant required privileges to the application user
GRANT USAGE ON SCHEMA public TO recipes_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO recipes_user;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO recipes_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO recipes_user;
