CREATE TABLE queue (
    phone_number  varchar(20) NOT NULL,
    qr_code       varchar(200) NOT NULL,
    email         varchar(200),
    first_name    varchar(200) NOT NULL,
    last_name     varchar(200) NOT NULL,
    rate          integer,
    time          integer NOT NULL,
    status        boolean,
    review        varchar(500),
    picture       varchar(500)
);