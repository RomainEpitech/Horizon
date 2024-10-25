<?php

    use Horizon\App\Models\User;

    // $users = User::findBy([
    //     'id' => '2',
    // ])
    //     ->orderBy(['created_at' => 'ASC'])
    //     ->limit(10)
    //     ->get();

    $users = User::all();
    var_dump($users);