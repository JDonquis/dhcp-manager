<?php

namespace App\Enums;

use App\Notifications\DepartmentCreatedNotification;
use App\Notifications\DepartmentDeletedNotification;
use App\Notifications\DepartmentUpdatedNotification;
use App\Notifications\GroupCreatedNotification;
use App\Notifications\GroupDeletedNotification;
use App\Notifications\GroupUpdatedNotification;
use App\Notifications\HostCreatedNotification;
use App\Notifications\HostDeletedNotification;
use App\Notifications\HostUpdatedNotification;
use App\Notifications\NetworkUpdatedNotification;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserDeletedNotification;
use App\Notifications\UserUpdatedNotification;

enum TypeNotification: string
{
    case HOST_CREATED = 'host_created';
    case HOST_UPDATED = 'host_updated';
    case HOST_DELETED = 'host_deleted';

    case NETWORK_UPDATED = 'network_updated';

    case DEPARTMENT_CREATED = 'department_created';
    case DEPARTMENT_UPDATED = 'department_updated';
    case DEPARTMENT_DELETED = 'department_deleted';

    case GROUP_CREATED = 'group_created';
    case GROUP_UPDATED = 'group_updated';
    case GROUP_DELETED = 'group_deleted';

    case USER_CREATED = 'user_created';
    case USER_UPDATED = 'user_updated';
    case USER_DELETED = 'user_deleted';





    public function createNotification($model = null){
        
        return match($this) {
            
            self::HOST_CREATED => new HostCreatedNotification($model),
            self::HOST_UPDATED => new HostUpdatedNotification($model),
            self::HOST_DELETED => new HostDeletedNotification($model),

            self::NETWORK_UPDATED => new NetworkUpdatedNotification(),

            self::DEPARTMENT_CREATED => new DepartmentCreatedNotification($model),
            self::DEPARTMENT_UPDATED => new DepartmentUpdatedNotification($model),
            self::DEPARTMENT_DELETED => new DepartmentDeletedNotification($model),

            self::GROUP_CREATED => new GroupCreatedNotification($model),
            self::GROUP_UPDATED => new GroupUpdatedNotification($model),
            self::GROUP_DELETED => new GroupDeletedNotification($model),

            self::USER_CREATED => new UserCreatedNotification($model),
            self::USER_UPDATED => new UserUpdatedNotification($model),
            self::USER_DELETED => new UserDeletedNotification($model),

        };
    }
}