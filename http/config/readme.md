***Important!***          
Don't forget to add `db.php` file with the following content:                
```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=info_table',
    'username' => 'user',
    'password' => 'password',
    'charset' => 'utf8',
];

```
