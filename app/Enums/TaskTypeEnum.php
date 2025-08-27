<?php

namespace App\Enums;

enum TaskTypeEnum : string
{
    case WORK_REMINDER_TO_ENGINEER = "work_reminder_to_engineer";

    case WORK_UPDATE_REMINDER = "work_update_reminder";

    case ENGINEER_WORK_CLOSE_REMINDER = "engineer_work_close_reminder";


    case WORK_REMINDER_FOR_120 = "120";
    case WORK_REMINDER_FOR_30 = "30";
    case WORK_REMINDER_FOR_15 = "15";
    case WORK_REMINDER_FOR_60 = "60";
}
