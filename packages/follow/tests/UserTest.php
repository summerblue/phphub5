<?php
namespace Smartisan\Follow\Test;

use Illuminate\Database\Eloquent\Model;
use Smartisan\Follow\FollowTrait;

class UserTest extends Model
{
    use FollowTrait;
    
    protected $table = 'users';
    
    protected $fillable = ['name'];
}