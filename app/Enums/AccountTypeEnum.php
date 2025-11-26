<?php

namespace App\Enums;

enum AccountTypeEnum : int
{
    case Student = 0;
    case Admin = 1;
    case Doctor = 2;
    case Nurse = 3;
}
