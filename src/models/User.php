<?php

namespace App\Models;


final class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'UserID';
    public $incrementing = true; // primary key autoincrement

    protected $fillable = [
        'UserFirstName',
        'UserLastName',
        'UserEmail',
        'UserPassword',
        'UserIsActive',
    ];
}