# Horizon

./Horizon serv:run run Horizon server
./Horizon build:run guide you through Horizon set-up
./Horizon entities:make Fetch database for entities generation
./Horizon reset:run set back Horizon to default version
./Horizon log:clear empty the log.txt file
./Horizon controller:new [name]create a basic controller architecture
./Horizon form:create [name] create a basic form architecture
./Horizon migration:new create a basic migration architecture 
./Horizon migration:run Run the latest migration.

## Migrations
use with arrays such as 
creatTable('product', [
    'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
    'price' => 'INT',
    'name' => 'VARCHAR(255) NOT NULL'
]);
DeleteTable('tableName');
UpdateTable('Table', [
    'key' => 'value'
]);
or
addSQl('product', [
    'price' => xx,
    'name' => product_name
]);
removeSQl('product', [
    'price' => xx,
    'name' => product_name
]);
updateSQl('product', [
    'price' => xx,
    'name' => product_name
]);

## Auth
Auth::registerUser($params);
Params must be instance a Request class. It uses the array to define all the keys => values to input in Database.
Password is automatically verified and unset from request Data.

## Request
Request->IsMethod('Method');
Verify if the method used is right.

$Data = Request::getFields();
Retrieve, sanitize all the fields from a form.

## Mystic
Mystic::fetchAll(Entities::class);
Mystic::insert(Entities::class, $params);

## Lucid
Templating works by putting all the balises within {} and adding @ for conditions,etc.
