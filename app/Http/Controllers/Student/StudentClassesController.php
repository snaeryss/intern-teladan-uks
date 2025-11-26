<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Contracts\View\View;

class StudentClassesController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = 'Students Classes';
        $studentClasses = StudentClass::select([
                'class_level',
                'class_name',
                'group_year',
                'school_level',
            ])
            ->distinct()
            ->orderBy('group_year')
            ->orderBy('school_level')
            ->orderBy('class_level')
            ->orderBy('class_name')
            ->get();
        return view('students.classes.index', compact(
            'title',
            'studentClasses',
        ));
    }

    /**
     * @param string $classLevel
     * @param string $className
     * @param string $groupYear
     * @return View
     */
    public function show(
        string $classLevel,
        string $className,
        string $groupYear,
    ): View
    {
        $title = 'Class Detail';
        $students = Student::
        whereRelation('studentClasses', static function ($query) use ($classLevel, $className, $groupYear) {
            $query->where('class_level', $classLevel)
                ->where('class_name', $className)
                ->whereRaw("SUBSTRING(group_year, LENGTH(group_year) - 1) = ?", [$groupYear]);
        })
            ->orderBy('group_year')
            ->orderBy('nis')
            ->get();

        return view('students.classes.detail', compact(
            'title',
            'classLevel',
            'className',
            'groupYear',
            'students',
        ));
    }
}
