<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Groups extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'citygroup';

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'group',
        'city',
    ];

    public function getAllGroups()
    {
        return DB::table('citygroup')->query()
            ->get();
    }

    public function getLastGroupId()
    {
        return DB::table('citygroup')->orderBy('id', 'DESC')->first();
    }

    public function checkGroupNameByUser($username, $groupName)
    {
        return DB::table('citygroup')->where('username', '=', $username)->where('groupname', '=', $groupName)->get();
    }

    public function insertRegistry($id, $insertCidades, $groupName, $username)
    {
        return DB::table('citygroup')->insert([
            'group' => $id,
            'city' => $insertCidades,
            'groupName' => $groupName,
            'username' => $username
        ]);
    }

    public function searchGroupsByGroupId($id)
    {
        return DB::table('citygroup')->where('group', $id)->get();
    }

    public function searchGroupsByUser($username)
    {
        return DB::table('citygroup')->where('username', '=', $username)->orderBy('id')->get();
    }

    public function editById($id)
    {
        return DB::table('citygroup')->where('group', '=', $id)->orderBy('id')->get();
    }

    public function deleteById($id)
    {
        return DB::table('citygroup')->where('id', $id)->delete();
    }

    public function deleteByGroupId($id)
    {
        return DB::table('citygroup')->where('group', $id)->delete();
    }

    public function updateById($id, $cityName, $groupName)
    {
        return DB::table('citygroup')->where('id', $id)->update(['city' => $cityName, 'groupName' => $groupName]);
    }
}
