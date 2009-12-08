ALTER TABLE recipe_stores DROP CONSTRAINT recipe_stores_pkey;
CREATE SEQUENCE recipe_store_id_seq;
ALTER TABLE recipe_stores ADD COLUMN store_id INTEGER;
ALTER TABLE recipe_stores ALTER COLUMN store_id SET DEFAULT nextval('recipe_store_id_seq');

# Note Manually update the store_id values for each line
UPDATE recipe_stores SET store_id = 1...2...3...4 WHERE store_name='default';

ALTER TABLE recipe_stores ADD PRIMARY KEY (store_id);

UPDATE recipe_settings SET setting_version='2.25';
