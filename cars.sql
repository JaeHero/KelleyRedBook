USE jhero;


DROP TABLE IF EXISTS car;

CREATE TABLE car(
car_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
make VARCHAR(40) NOT NULL,
model VARCHAR(80) NOT NULL,
model_year VARCHAR(80) NOT NULL,
base_price INTEGER
);

insert into car (make, model, model_year, base_price) VALUES ('hyundai', 'sonata', '2011', 6500);
insert into car (make, model, model_year, base_price) VALUES ('toyota', 'tacoma', '2010', 750);
insert into car (make, model, model_year, base_price) VALUES ('toyota', 'corolla', '2013', 2000);
insert into car (make, model, model_year, base_price) VALUES ('honda', 'civic', '2010', 1000);
insert into car (make, model, model_year, base_price) VALUES ('ford', 'fiesta', '2014', 2800);
insert into car (make, model, model_year, base_price) VALUES ('ford', 'focus', '2014', 1500);
insert into car (make, model, model_year, base_price) VALUES ('volkswagon', 'beetle', '2011', 4250);
insert into car (make, model, model_year, base_price) VALUES ('bmw', '3series', '2018', 22500);
insert into car (make, model, model_year, base_price) VALUES ('volkswagon', 'passat', '2011', 3500);
insert into car (make, model, model_year, base_price) VALUES ('toyota', 'hiluk', '2012', 2750);
insert into car (make, model, model_year, base_price) VALUES ('vaz', '2101', '2020', 5500);
insert into car (make, model, model_year, base_price) VALUES ('ford', 'escort', '2011', 4600);
insert into car (make, model, model_year, base_price) VALUES ('subaru', 'legacy', '2016', 11000);
insert into car (make, model, model_year, base_price) VALUES ('kia', 'sorento', '2018', 29350);
insert into car (make, model, model_year, base_price) VALUES ('lexus', 'gx', '2019', 64250);
insert into car (make, model, model_year, base_price) VALUES ('nissan', 'rouge', '2015', 19450);
insert into car (make, model, model_year, base_price) VALUES ('subaru', 'brz', '2011', 15555);
insert into car (make, model, model_year, base_price) VALUES ('tesla', 'cybertruck', '2024', 85000);
insert into car (make, model, model_year, base_price) VALUES ('cadillac', 'escalade', '2020', 38999);
insert into car (make, model, model_year, base_price) VALUES ('volvo', 'ex30', '2022', 34950);


DROP TABLE IF EXISTS car_users;
CREATE TABLE car_users (
adjusted_price INTEGER(40),
car_ID INTEGER UNSIGNED NOT NULL,
user_ID INTEGER UNSIGNED NOT NULL,
PRIMARY KEY(car_ID, user_ID),
FOREIGN KEY (car_ID) REFERENCES car(car_id),
FOREIGN KEY (user_ID) REFERENCES usersKelly(user_id)
);

