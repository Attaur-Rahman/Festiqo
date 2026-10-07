<?php

namespace App\Enums;

enum Permission: string
{
    // Dashboard
    case DASHBOARD_VIEW = 'dashboard.view';

        // Users
    case USER_VIEW = 'user.view';
    case USER_CREATE = 'user.create';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';

        // Category
    case CATEGORY_VIEW = 'category.view';
    case CATEGORY_CREATE = 'category.create';
    case CATEGORY_UPDATE = 'category.update';
    case CATEGORY_DELETE = 'category.delete';

        // Events
    case EVENT_VIEW = 'event.view';
    case EVENT_CREATE = 'event.create';
    case EVENT_UPDATE = 'event.update';
    case EVENT_DELETE = 'event.delete';

        // Registrations
    case REGISTRATION_VIEW = 'registration.view';
    case REGISTRATION_VERIFY = 'registration.verify';

        // Results
    case RESULT_MANAGE = 'result.manage';

        // Certificates
    case CERTIFICATE_GENERATE = 'certificate.generate';
    case CERTIFICATE_DOWNLOAD = 'certificate.download';
}
