create table airlines
(
  id          int auto_increment
    primary key,
  name        varchar(50)  not null,
  description varchar(100) not null
);

create table cities
(
  id      int auto_increment
    primary key,
  name    varchar(50) not null,
  state   varchar(50) not null,
  country varchar(50) not null
);

create table reservations
(
  id                      int auto_increment
    primary key,
  departure_city_id       int      not null,
  departure_date          datetime not null,
  arrival_city_id         int      not null,
  arrival_date            datetime not null,
  airline_id              int      not null,
  reservation_expire_date datetime not null,
  constraint airlines_id_on_reservations___fk
    foreign key (airline_id) references airlines (id),
  constraint arrival_city_id_on_reservations___fk
    foreign key (arrival_city_id) references cities (id),
  constraint departure_city_id_on_reservations___fk
    foreign key (departure_city_id) references cities (id)
);


INSERT INTO airlines (id, name, description) VALUES (1, 'Aerolineas Argentinas', 'Aerolineas Argentinas');
INSERT INTO airlines (id, name, description) VALUES (2, 'American Airlines', 'American Airlines');
INSERT INTO airlines (id, name, description) VALUES (3, 'Sol', 'Sol');
INSERT INTO airlines (id, name, description) VALUES (4, 'Turkish Airlines', 'Turkish Airlines');
INSERT INTO airlines (id, name, description) VALUES (5, 'Qatar Airlines', 'Qatar Airlines');
INSERT INTO airlines (id, name, description) VALUES (6, 'Air Europa', 'Air Europa');
INSERT INTO airlines (id, name, description) VALUES (7, 'Aeroméxico', 'Aeroméxico');
INSERT INTO airlines (id, name, description) VALUES (8, 'Latam', 'Latam');
INSERT INTO cities (id, name, state, country) VALUES (1, 'Buenos Aires', 'Ciudad de Buenos Aires', 'Argentina');
INSERT INTO cities (id, name, state, country) VALUES (2, 'Mar del Plata', 'Buenos Aires', 'Argentina');
INSERT INTO cities (id, name, state, country) VALUES (3, 'Pinamar', 'Buenos Aires', 'Argentina');
INSERT INTO cities (id, name, state, country) VALUES (4, 'Cariló', 'Buenos Aires', 'Argentina');
