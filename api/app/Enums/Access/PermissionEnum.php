<?php

namespace App\Enums\Access;

enum PermissionEnum: string
{
    // Posts
    case ViewPosts = 'view posts';
    case CreatePosts = 'create posts';
    case EditPosts = 'edit posts';
    case DeletePosts = 'delete posts';
    case PublishPosts = 'publish posts';

    // Users
    case ViewUsers = 'view users';

    // Staff / Editors
    case CreateEditors = 'create editors';
    case EditEditors = 'edit editors';
    case DeleteEditors = 'delete editors';

    // Admins
    case CreateAdmins = 'create admins';
    case EditAdmins = 'edit admins';
    case DeleteAdmins = 'delete admins';

    // Roles
    case ManageRoles = 'manage roles';
}
