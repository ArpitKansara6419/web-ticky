<?php

namespace App\Enums;

enum HolidayTypeEnum : string
{
    case OBSERVANCE = 'Observance';
    case PUBLIC = 'Public';
    case NATIONAL_HOLIDAY = 'National holiday';

    // "primary_type": "Christian",
    // "primary_type": Gazetted Holiday
    // type : 'National holiday'
    // National holiday, Hinduism

}
