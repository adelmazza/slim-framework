# slim-framework

Skeleton based on  Slim 3 and the following libraries

- slim/twig-view
- slim/flash
- tracy
- nesbot/carbon
- monolog
- illuminate/database
- vlucas/valitron
- swiftmailer

**Rename the configuration file:** *src/config.example.php* to *src/config.php* 

## Users table

The example model provided is based on the following MySQL table structure:

```
create table users
(
	UserID int auto_increment primary key,
	UserFirstName varchar(150) not null,
	UserLastName varchar(200) not null,
	UserEmail varchar(200) not null,
	UserPassword varchar(200) not null,
	UserLastLoginDate datetime null,
	UserIsActive bit default b'0' not null,
	CreatedAt datetime null,
	UpdatedAt datetime null,
	DeletedAt datetime null,
	constraint users_UserEmail_uindex unique (UserEmail)
);
```
