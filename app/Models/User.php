<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = "users";

    protected $fillable = ['name', 'username', 'password', 'email'];

    public static function find($id) {
        // Assume $db is a database connection instance (PDO, for example)
        $user = User::where('id', $id)->first();
        return $user;
    }

    public static function findByUsername($username) {
        // Assume $db is a database connection instance (PDO, for example)
        $user = User::where('username', $username)->first();
        return $user;
    }
}
