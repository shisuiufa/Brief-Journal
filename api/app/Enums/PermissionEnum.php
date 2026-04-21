<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CreatePosts = 'create posts';
    case EditPosts = 'edit posts';
    case DeletePosts = 'delete posts';
    case PublishPosts = 'publish posts';
    case ManageUsers = 'manage users';
    case ManageRoles = 'manage roles';
}
