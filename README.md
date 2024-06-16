# Horizon

## Migrations

use with arrays such as 
creatTable(['product'], [
    'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
    'price' => 'INT',
    'name' => 'VARCHAR(255) NOT NULL'
]);
or
addSQl(['product'], [
    'price' => xx,
    'name' => product_name
]);