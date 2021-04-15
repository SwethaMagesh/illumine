
create database if not exists illumine;
use illumine;
create table BOOKS
( BID int primary key auto_increment,
Title varchar(100),
Genre varchar(50),
Year int,
AID int,
Cover blob
);
alter table books 
modify Title varchar(100) unique;
create table AUTHOR(
AID int primary key auto_increment,
Name varchar(50)
);
alter table author 
modify name varchar(50) unique;

create table USER(
UID int primary key auto_increment,
Name varchar(30),
Email varchar(50) ,
Pwd varchar(20)
);
alter table user
modify email varchar(50) unique;

create table UserBookshelf(
UID int,
BID int,
STATUS varchar(10),
Rating int, 
OneLineReview varchar(200),
SuggestedBy varchar(50),
LessonLearnt varchar(150),
primary key(UID, BID),
foreign key (UID) references USER(Uid) on delete cascade,
foreign key (BID) references BOOKS(BID) on delete cascade
);
create table USER_BOOKQuotes(
UID int ,
BID int , 
Quote varchar(200),
primary key(UID,BID,Quote),
foreign key (BID) references BOOKS(bid) on delete cascade,
foreign key (uid) references USER(uid) on delete cascade
);
alter table books
add constraint fkey foreign key (aid) references author(aid) on delete cascade;