# Daily-Expenses

## Create an user from laravel:
use App\Models\User;

User::create([
    'name' => 'Ny Antsa',
    'username' => 'Administrator', 
    'email' => 'antsamadagascar@gmail.com',
    'password' => bcrypt('1234'),
]);
