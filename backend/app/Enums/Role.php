<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case EVENT_COORDINATOR = 'event_coordinator';
    case STUDENT_COORDINATOR = 'student_coordinator';
}
