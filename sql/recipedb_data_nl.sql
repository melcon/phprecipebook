# Groepen (doel onbekend)
INSERT INTO security_groups (group_name) VALUES('Vrienden');
INSERT INTO security_groups (group_name) VALUES('Familie');
INSERT INTO security_groups (group_name) VALUES('Beheerders');

# Oorsprong van recept.
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Italiaans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Chinees');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Japans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Mexicaans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Amerikaans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Frans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Engels');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Spaans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Indonesisch');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Balkan');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Grieks');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Schots');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Duits');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Belgisch');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Portugees');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Zuid-Amerikaans');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Afrikaans');

# Maateenheden
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (0,'Eenheid','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (1,'Kop','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (2,'Eetlepel','el');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (3,'Theelepel','tl');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (4,'Snufje','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (5,'Pond','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (6,'Ons','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (7,'Gram','gr');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (8,'Kilogram','kg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (9,'Milliliter','ml');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (10,'Liter','l');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (11,'Plak','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (12,'Druppel','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (13,'Sneetje','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (14,'Teentje','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (15,'Miligram','mg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (16,'Centigram','cg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (17,'Centiliter','cl');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (18,'Deciliter','dl');

# Onderstaand de plaats in de winkel waar producten te vinden zijn.
INSERT INTO recipe_locations (location_desc) VALUES ('Blik');
INSERT INTO recipe_locations (location_desc) VALUES ('Brood');
INSERT INTO recipe_locations (location_desc) VALUES ('Broodbeleg');
INSERT INTO recipe_locations (location_desc) VALUES ('Chips');
INSERT INTO recipe_locations (location_desc) VALUES ('Delicatesse');
INSERT INTO recipe_locations (location_desc) VALUES ('Diepvries');
INSERT INTO recipe_locations (location_desc) VALUES ('Dressing');
INSERT INTO recipe_locations (location_desc) VALUES ('Frisdrank');
INSERT INTO recipe_locations (location_desc) VALUES ('Fruit');
INSERT INTO recipe_locations (location_desc) VALUES ('Groenten');
INSERT INTO recipe_locations (location_desc) VALUES ('Kaas');
INSERT INTO recipe_locations (location_desc) VALUES ('Koekjes');
INSERT INTO recipe_locations (location_desc) VALUES ('Koffie/Thee');
INSERT INTO recipe_locations (location_desc) VALUES ('Kruiden');
INSERT INTO recipe_locations (location_desc) VALUES ('Olie');
INSERT INTO recipe_locations (location_desc) VALUES ('Ontbijtgranen');
INSERT INTO recipe_locations (location_desc) VALUES ('Pasta');
INSERT INTO recipe_locations (location_desc) VALUES ('Sauzen');
INSERT INTO recipe_locations (location_desc) VALUES ('Rijst');
INSERT INTO recipe_locations (location_desc) VALUES ('Sap');
INSERT INTO recipe_locations (location_desc) VALUES ('Soep');
INSERT INTO recipe_locations (location_desc) VALUES ('Specerijen');
INSERT INTO recipe_locations (location_desc) VALUES ('Sterke drank');
INSERT INTO recipe_locations (location_desc) VALUES ('Vis');
INSERT INTO recipe_locations (location_desc) VALUES ('Vlees');
INSERT INTO recipe_locations (location_desc) VALUES ('Vlees, voorverpakt');
INSERT INTO recipe_locations (location_desc) VALUES ('Vleeswaren');
INSERT INTO recipe_locations (location_desc) VALUES ('Vleeswaren, voorverpakt');
INSERT INTO recipe_locations (location_desc) VALUES ('Zuivel');

# Basis van recept (komt links in het hoofdmenu)
INSERT INTO recipe_bases (base_desc) VALUES ('Gevogelte');
INSERT INTO recipe_bases (base_desc) VALUES ('Rundvlees');
INSERT INTO recipe_bases (base_desc) VALUES ('Lamsvlees');
INSERT INTO recipe_bases (base_desc) VALUES ('Varkensvlees');
INSERT INTO recipe_bases (base_desc) VALUES ('Vis');
INSERT INTO recipe_bases (base_desc) VALUES ('Vegetarisch');
INSERT INTO recipe_bases (base_desc) VALUES ('Groenten');
INSERT INTO recipe_bases (base_desc) VALUES ('Fruit');
INSERT INTO recipe_bases (base_desc) VALUES ('Anders');

# Bereidingstijd
INSERT INTO recipe_prep_time (time_desc) VALUES ('0-10 Minuten');
INSERT INTO recipe_prep_time (time_desc) VALUES ('10-20 Minuten');
INSERT INTO recipe_prep_time (time_desc) VALUES ('30-60 Minuten');
INSERT INTO recipe_prep_time (time_desc) VALUES ('60+ Minuten');

# Moeilijkheidsgraad
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Eenvoudig');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Gevorderd');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Moeilijk');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Expert');

# Gang van maaltijd
INSERT INTO recipe_courses (course_desc) VALUES ('Ontbijt');
INSERT INTO recipe_courses (course_desc) VALUES ('Brunch');
INSERT INTO recipe_courses (course_desc) VALUES ('Lunch');
INSERT INTO recipe_courses (course_desc) VALUES ('Voorgerecht');
INSERT INTO recipe_courses (course_desc) VALUES ('Hoofdgerecht');
INSERT INTO recipe_courses (course_desc) VALUES ('Bijgerecht');
INSERT INTO recipe_courses (course_desc) VALUES ('Nagerecht');
INSERT INTO recipe_courses (course_desc) VALUES ('Dessert');
INSERT INTO recipe_courses (course_desc) VALUES ('Tussendoortje');
INSERT INTO recipe_courses (course_desc) VALUES ('Drank');
INSERT INTO recipe_courses (course_desc) VALUES ('Amuse');

# Maaltijd (doel nog onbekend)
INSERT INTO recipe_meals (meal_name) VALUES ('Ontbijt');
INSERT INTO recipe_meals (meal_name) VALUES ('Brunch');
INSERT INTO recipe_meals (meal_name) VALUES ('Lunch');
INSERT INTO recipe_meals (meal_name) VALUES ('Snack');
INSERT INTO recipe_meals (meal_name) VALUES ('Avondeten');
INSERT INTO recipe_meals (meal_name) VALUES ('Toetje');

# Prijzen (voorlopig nog in dollars)
INSERT INTO recipe_prices (price_desc) VALUES ('$0-$10');
INSERT INTO recipe_prices (price_desc) VALUES ('$10-$15');
INSERT INTO recipe_prices (price_desc) VALUES ('$15-$20');
INSERT INTO recipe_prices (price_desc) VALUES ('$20-$25');
INSERT INTO recipe_prices (price_desc) VALUES ('$25-$30');
INSERT INTO recipe_prices (price_desc) VALUES ('$30+');
