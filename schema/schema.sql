create database if not exists books_share;
use books_share;

create table if not exists users ();

create table if not exists books (
    book_id integer unsigned primary key auto_increment,
    title varchar(500) not null,
    author_id integer unsigned not null
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;

create table if not exists authors (
    author_id integer unsigned primary key auto_increment,
    name varchar(100) not null,
    nationality varchar(2)
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;

create table if not exists clubs (
    clubs_id integer unsigned primary key auto_increment,
    name varchar(100) not null unique,
    description varchar(500),
    created_at timestamp not null default current_timestamp,
    updated_at timestamp not null default current_timestamp 
        on update current_timestamp
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;

create table if not exists club_members (
    club_members_id integer unsigned primary key auto_increment,
    user_id integer unsigned not null,
    club_id integer unsigned not null,
    is_admin tinyint not null default 0,
    created_at timestamp not null default current_timestamp,
    updated_at timestamp not null default current_timestamp 
        on update current_timestamp,
    unique key no_rep(user_id, club_id)
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;

create table if not exists user_books (
    user_books_is integer unsigned primary key auto_increment,
    user_id integer not null,
    book_id integer not null,
    created_at timestamp not null default current_timestamp,
    modified_at timestamp not null default current_timestamp 
        on update current_timestamp,
    unique key no_rep(user_id, book_id)
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;

create table if not exists book_scores (
    book_score_id integer unsigned primary key auto_increment,
    user_id integer unsigned not null,
    book_id integer unsigned not null,
    score tinyint unsigned,
    created_at timestamp not null default current_timestamp,
    modified_at timestamp not null default current_timestamp on update current_timestamp,
    unique key no_rep(user_id, book_id)
) Engine=InnoDB default charset=utf8mb4 collate=utf8mb4_bin;