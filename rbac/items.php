<?php
return [
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'UpdatePost' => [
        'type' => 2,
        'description' => 'Update a post',
    ],
    'UpdateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'UpdatePost',
        ],
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'createPost',
            'UpdateOwnPost',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'UpdatePost',
            'user',
        ],
    ],
];
