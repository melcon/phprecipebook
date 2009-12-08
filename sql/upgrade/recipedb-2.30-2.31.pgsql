ALTER TABLE recipe_restaurants ADD COLUMN restaurant_country VARCHAR(64);
UPDATE recipe_settings SET setting_version='2.31';
