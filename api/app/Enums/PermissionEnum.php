<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CREATE_POSTS = 'create posts';
    case EDIT_POSTS = 'edit posts';
    case DELETE_POSTS = 'delete posts';
    case PUBLISH_POSTS = 'publish posts';
    case MANAGE_USERS = 'manage users';
    case MANAGE_ROLES = 'manage roles';
}
