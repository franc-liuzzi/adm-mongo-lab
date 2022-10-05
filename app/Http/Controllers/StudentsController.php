<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMarkRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class StudentsController extends Controller
{
    public static function routes(): void
    {
        Route::get('students', [static::class, 'listStudents']);
        Route::get('students/{student_id}', [static::class, 'getStudent'])->name('get-student');
        Route::get('students/{student_id}/marks/{subject}', [static::class, 'getStudentMarksBySubject']);
        Route::get('students/{student_id}/marks/{subject}/avg', [static::class, 'getStudentsAvgMarkBySubject']);
        Route::post('students/{student_id}/marks', [static::class, 'addMarkForStudent']);
    }
    protected function getStudentsAvgMarkBySubject($student_id, $subject): JsonResource {
        $student = Student::where(['_id' => $student_id])
            ->project([
                "avg_$subject" => [
                    '$avg' => [ // ho fatto la media
                        '$map' => [ // ho trasformato in un array di soli voti
                            'input' => [ 
                                '$filter' => [ // ho filtrato i voti della materia
                                    'input' => '$voti',
                                    'as' => 'votoObj',
                                    'cond' => [
                                        '$eq' => ['$$votoObj.materia', $subject],
                                    ]
                                ]
                            ],
                            'as' => 'votoObj',
                            'in' => '$$votoObj.voto'
                        ]
                    ]
                ]
            ])
            ->get();
        
        return JsonResource::make($student);
    }

    protected function listStudents(): JsonResource
    {
        $students = Student::paginate(
            request()->query('per_page', 10),
            [
                'first_name',
                'last_name',
                'birthdate',
            ]
        );
        return JsonResource::make($students);
    }

    protected function getStudent($student_id): JsonResource
    {
        $student = Student::find($student_id);
        return JsonResource::make($student);
    }

    protected function getStudentMarksBySubject($student_id, $subject): JsonResource
    {
        $student = Student::where(['_id' => $student_id])
            ->project([
                'voti' => [
                    '$filter' => [
                        'input' => '$voti',
                        'as' => 'votoObj',
                        'cond' => [
                            '$eq' => ['$$votoObj.materia', $subject]
                        ],
                    ],
                ],
            ])
            ->get();

        return JsonResource::make($student);
    }

    protected function addMarkForStudent($student_id, AddMarkRequest $request) {
        $result = Student::where([ '_id' => $student_id ])
            ->push('voti', $request->safe()->toArray());
        if ($result) {
            return redirect("/students/$student_id");
        } else {
            return abort(400);
        }
    }
}
