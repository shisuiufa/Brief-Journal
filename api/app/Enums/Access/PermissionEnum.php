<?php

namespace App\Enums\Access;

enum PermissionEnum: string
{
    case CreatePosts = 'create posts';
    case EditPosts = 'edit posts';
    case DeletePosts = 'delete posts';
    case PublishPosts = 'publish posts';
    case ManageUsers = 'manage users';
    case ManageRoles = 'manage roles';
}
