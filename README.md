# slim-framework

Skeleton based on  Slim 3 and the following libraries

- [slim/twig-view](https://github.com/slimphp/Twig-View)
- [slim/flash](https://github.com/slimphp/Slim-Flash)
- [nette/tracy](https://github.com/nette/tracy)
- [nesbot/carbon](https://github.com/briannesbitt/Carbon)
- [monolog/monolog](https://github.com/Seldaek/monolog)
- [illuminate/database](https://github.com/illuminate/database)
- [vlucas/valitron](https://github.com/vlucas/valitron)
- [swiftmailer/swiftmailer](https://github.com/swiftmailer/swiftmailer)

**Rename the configuration file:** *src/config.example.php* to *src/config.php* 

## Users table

The example model provided is based on the following MySQL table structure:

```mysql
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
## Code snippets

**Send email inside a controller:**

```php
$message = \Swift_Message::newInstance('mail subject')
            ->setFrom('from@example.com')
            ->setTo('to@example.com')
            ->setBody('body', 'text/html')
            ->setCharset('UTF-8');

$this->swiftmailer->send($message);
```

**Session set/get vars inside controllers:**

```php
$this->session->name = 'John';
echo $this->session->name;
```
```php
$this->session->set('name' , 'John');
echo $this->session->get('name');
```

```php
$this->session->set('data', ['one' => 1, 'two' => 'Two']);
echo $this->session->get('data');
```

**Flash messages inside controllers:**

```php
$this->flash->addMessage('info', 'my info message');
$this->flash->addMessage('warning', 'my warning message');
$this->flash->addMessage('error', 'my error message');
```

```php
echo $this->flash->getMessage('error');
```

**Queries:**

Raw queries
```php
use Illuminate\Database\Capsule\Manager as DB;
DB::select('select * from users');
DB::statement('truncate table users');
```

Using model:
```php
User::where('UserID', 1)->get();
User::where('UserEmail', 'name@example.com')->first();
User::create([
    'UserEmail' => $request->getParam('UserEmail'),
    ...
]);
```

Another example

```php
$rs = $this->db->table('customers')->get();
```

**Debugger:**

Nice dump
```php
use Tracy\Debugger;
Debugger::dump(User::where('UserID', 1)->get());
Debugger::dump(['one' => 1, 'two' => 'Two']);
```

Dump to Tracy bar
```php
Debugger::barDump($request->getParsedBody(), 'ParsedBody');
Debugger::barDump(['one' => 1, 'two' => 'Two']);
```

**Monolog inside controllers:**

```php
$this->logger->debug('my message');
```