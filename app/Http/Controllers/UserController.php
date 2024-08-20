<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all();
    }

    public function user(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return User::query()->where('id', $id)->firstOrFail();
    }

    public function teachers() {
        $teachers = User::query()->where('role', 'teacher')->get();
        return $teachers;
    }

    public function teachersDelete($id) {
        User::query()->where('id', $id)->delete();
        return response(['successful'], 200);
    }

    public function students() {
        $students = User::query()->where('role', 'student')->get();
        return $students;
    }
}
