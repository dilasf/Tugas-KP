<?php
namespace App\Observers;

use App\Models\Student;
use App\Models\StudentClass;

class StudentObserver
{
    public function created(Student $student)
    {
        $this->updateClassCounts($student->class_id);
    }

    public function updated(Student $student)
    {
        // Check if class_id has changed
        if ($student->isDirty('class_id')) {
            $this->updateClassCounts($student->getOriginal('class_id'));
        }
        $this->updateClassCounts($student->class_id);
    }

    public function deleted(Student $student)
    {
        $this->updateClassCounts($student->class_id);
    }

    protected function updateClassCounts($classId)
    {
        $class = StudentClass::find($classId);

        if ($class) {
            $class->number_of_students = Student::where('class_id', $classId)->count();
            $class->number_of_male_students = Student::where('class_id', $classId)->where('gender', 'Laki-laki')->count();
            $class->number_of_female_students = Student::where('class_id', $classId)->where('gender', 'Perempuan')->count();
            $class->save();
        }
    }
}
