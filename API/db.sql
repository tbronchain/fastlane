CREATE TABLE queue (
    qrcode       varchar(200) NOT NULL,
    phone         integer NOT NULL,
    email   varchar(200),
    firstname        varchar(200) NOT NULL,
    lastname         varchar(200) NOT NULL,
    rate         integer,
    time         integer NOT NULL,
    status     boolean,
    review    varchar(500),
    picture    varchar(500)
);