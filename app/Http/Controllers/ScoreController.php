<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Score;
use App\Models\ScoreDetail;
use App\Models\Study;
use App\Models\StudyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreController extends Controller
{
    public function form(StudyClass $study_class, Request $request)
    {
        $scores = Score::whereStudyClassId($study_class->id)->where('type', $request->type)->when($request->type === "0", function($q) use ($request) {
            return $q->whereSemester($request->semester);
        })->get()->load(['scoreDetails' => function($query) {
            $query->orderBy('subject_id');
        }, 'scoreDetails.subject', 'student']);

       $subjects = $study_class->load(['subjects' => function ($query) {
            $query->orderBy('subject_id');
       }])->subjects;

       $students = Study::with(['studyClass', 'student'])->whereStudyClassId($study_class->id)->whereNotIn('student_id', $scores->pluck('student_id'))->get();

        if(count($scores) === 0) {

            foreach($students as $k => $student) {
                $data[$k]['student_id'] = $student->student_id;
                $data[$k]['name'] = $student->student->first_name . ' ' . $student->student->last_name;
                $data[$k]['gender'] = $student->student->gender_label;
                foreach($subjects as $k2 => $subject) {
                    $data[$k]['details'][$k2]['id'] = $subject->id;
                    $data[$k]['details'][$k2]['name'] = $subject->name;
                    $data[$k]['details'][$k2]['score'] = null;
                }
            }

        } else {
            foreach($scores as $k => $score) {
                $data[$k]['id'] = $score->id;
                $data[$k]['student_id'] = $score->student->id;
                $data[$k]['name'] = $score->student->first_name . ' ' . $score->student->last_name;
                $data[$k]['gender'] = $score->student->gender_label;
                foreach($score->scoreDetails as $k2 => $detail) {
                    $data[$k]['details'][$k2]['id'] = $detail->subject_id;
                    $data[$k]['details'][$k2]['name'] = $detail->subject->name;
                    $data[$k]['details'][$k2]['score'] = $detail->score;
                }
            }
        }

        return response()->json([
            'data' => $data,
            'subjects' => $subjects->pluck('name'),
            'semester' => $request->semester ?? 1,
            'month_text' => filterMonth($request->type)
        ]);
    }

    public function save(StudyClass $study_class, Request $request)
    {

        DB::beginTransaction();

        try {

            foreach($request->details as $item) {
                $total = 0;
                $score = new Score();
                if(isset($item['id'])) {
                    $score = Score::findOrFail($item['id']);
                    ScoreDetail::where('score_id', $item['id'])->delete();
                }
                $score->study_class_id = $study_class->id;
                $score->student_id = $item['student_id'];
                $score->semester = $request->semester;
                $score->type = $request->type;
                $score->save();
                foreach($item['details'] as $sub) {
                    $score_detail = new ScoreDetail();
                    $score_detail->score_id = $score->id;
                    $score_detail->subject_id = $sub['id'];
                    $score_detail->score = $sub['score'];
                    $score_detail->save();
                    $total += $sub['score'];
                }
                $score->total = $total;
                $score->avg = $total / count($item['details']);
                $score->save();
            }

            DB::commit();

            return response()->json(['message' => 'Successfully save score.']);

        } catch(Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Cannot connect to remote server.'], 500);
        }
    }

    public function list(StudyClass $study_class, Request $request)
    {
        $scores = Score::whereStudyClassId($study_class->id)->where('type', $request->type)->when($request->type === "0", function($q) use ($request) {
            return $q->whereSemester($request->semester);
        })->get()->load(['scoreDetails' => function($query) {
            $query->orderBy('subject_id');
        }, 'scoreDetails.subject', 'student']);

       $subjects = $study_class->load(['subjects' => function ($query) {
            $query->orderBy('subject_id');
       }])->subjects;

       $students = Study::with(['studyClass', 'student'])->whereStudyClassId($study_class->id)->whereNotIn('student_id', $scores->pluck('student_id'))->get();

        if(count($scores) === 0) {

            foreach($students as $k => $student) {
                $data[$k]['student_id'] = $student->student_id;
                $data[$k]['name'] = $student->student->first_name . ' ' . $student->student->last_name;
                $data[$k]['gender'] = $student->student->gender_label;
                $data[$k]['total'] = 0;
                $data[$k]['avg'] = 0.00;
                foreach($subjects as $k2 => $subject) {
                    $data[$k]['details'][$k2]['id'] = $subject->id;
                    $data[$k]['details'][$k2]['name'] = $subject->name;
                    $data[$k]['details'][$k2]['score'] = null;
                }
            }

        } else {
            foreach($scores as $k => $score) {
                $data[$k]['id'] = $score->id;
                $data[$k]['student_id'] = $score->student->id;
                $data[$k]['name'] = $score->student->first_name . ' ' . $score->student->last_name;
                $data[$k]['gender'] = $score->student->gender_label;
                $data[$k]['total'] = 0;
                foreach($score->scoreDetails as $k2 => $detail) {
                    $data[$k]['details'][$k2]['id'] = $detail->subject_id;
                    $data[$k]['details'][$k2]['name'] = $detail->subject->name;
                    $data[$k]['details'][$k2]['score'] = $detail->score;
                    $data[$k]['total'] +=  $detail->score;
                }
                $data[$k]['avg'] =  $data[$k]['total'] / count($subjects);
            }
        }

        $scores = [
            [
                'avg' => null,
                'key' => null,
            ]
        ];

        foreach($data as $k => $item) {
            $scores[$k]['avg'] = $item['avg'];
            $scores[$k]['key'] = $k;
        }

        arsort($scores);

        $scores = array_values($scores);

        $rank = 1;
        $prev_rank = $rank;
        foreach($scores as $k => $score) {
            if($k < 1 ) {
                $data[$score['key']]['rank'] = $rank;
            }  else {
                if($scores[$k]['avg'] != $scores[$k-1]['avg']) {
                    $rank++;
                    $prev_rank = $rank;
                    $data[$score['key']]['rank'] = $rank;
                } else {
                    $rank++;
                    $data[$score['key']]['rank'] = $prev_rank;
                }
            }
        }

        return response()->json([
            'data' => $data,
            'subjects' => $subjects->pluck('detail'),
            'semester' => $request->semester ?? 1,
            'month_text' => filterMonth($request->type)
        ]);
    }

    public function ranking(StudyClass $study_class, Request $request)
    {

        $data = [
            [],[]
        ];

        if(!empty($request->total)) {

            if($request->total != 3) {
                $total_month = Score::whereStudyClassId($study_class->id)->where('semester', $request->total)->where('type', '!=', 0)->distinct('type')->count();

                $scores = Score::whereStudyClassId($study_class->id)->where('semester', $request->total)->where('type', '!=', 0)
                    ->join('students', 'students.id', 'scores.student_id')
                    ->selectRaw("SUM(avg) as avg, student_id, CONCAT(students.first_name, ' ', students.last_name) as name")
                    ->groupBy('student_id')
                    ->get();

                $semester_scores = Score::whereStudyClassId($study_class->id)->where('semester', $request->total)->where('type', 0)->get();
                foreach($semester_scores as $ss) {
                    foreach($scores as $k => $score) {
                        if($ss->student_id == $score->student_id) {
                            if($k < 36) {
                                $data[0][$k]['name'] = $score->name;
                                $data[0][$k]['avg'] = ($score->avg / $total_month + $ss->avg) / 2;

                            } else {
                                $data[1][$k]['name'] = $score->name;
                                $data[1][$k]['avg'] = ($score->avg / $total_month + $ss->avg) / 2;
                            }
                        }
                    }
                }
            } else {

                $semesters = [];

                for($i = 0; $i < 2; $i++) {
                    $total_month = Score::whereStudyClassId($study_class->id)->where('semester', $i + 1)->where('type', '!=', 0)->distinct('type')->count();

                    $semester_scores = Score::whereStudyClassId($study_class->id)->where('semester', $i + 1)->where('type', 0)->get();

                    $scores = Score::whereStudyClassId($study_class->id)->where('semester', $i + 1)->where('type', '!=', 0)
                        ->join('students', 'students.id', 'scores.student_id')
                        ->selectRaw("SUM(avg) as avg, student_id, CONCAT(students.first_name, ' ', students.last_name) as name")
                        ->groupBy('student_id')
                        ->get();

                    foreach($semester_scores as $ss) {

                        foreach($scores as $k => $score) {
                            if($ss->student_id == $score->student_id) {
                                $semesters[$k]['student_id'] = $score->student_id;
                                $semesters[$k]['name'] = $score->name;
                                $semesters[$k]['avg_s' . $i + 1] = ($score->avg / $total_month + $ss->avg) / 2;

                            }
                        }
                    }
                }
                foreach($semesters as $k => $s) {
                    if($k < 36) {
                        $data[0][$k]['name'] = $s['name'];
                        $data[0][$k]['avg'] = ($s['avg_s1'] + $s['avg_s2']) / 2;
                    } else {
                        $data[1][$k]['name'] = $s['name'];
                        $data[1][$k]['avg'] = ($s['avg_s1'] + $s['avg_s2']) / 2;
                    }

                }
            }

        } else {
            $scores = Score::whereStudyClassId($study_class->id)->where('type', $request->type)->when($request->type === "0", function($q) use ($request) {
                return $q->whereSemester($request->semester);
            })->get()->load(['scoreDetails' => function($query) {
                $query->orderBy('subject_id');
            }, 'scoreDetails.subject', 'student']);

            $students = Study::with(['studyClass', 'student'])->whereStudyClassId($study_class->id)->whereNotIn('student_id', $scores->pluck('student_id'))->get();

            if(count($scores) === 0) {
                foreach($students as $k => $student) {
                    if($k < 36) {
                        $data[0][$k]['student_id'] = $student->student_id;
                        $data[0][$k]['name'] = $student->student->first_name . ' ' . $student->student->last_name;
                        $data[0][$k]['gender'] = $student->student->gender_label;
                        $data[0][$k]['total'] = 0;
                        $data[0][$k]['avg'] = 0.00;
                    } else {
                        $data[1][$k]['student_id'] = $student->student_id;
                        $data[1][$k]['name'] = $student->student->first_name . ' ' . $student->student->last_name;
                        $data[1][$k]['gender'] = $student->student->gender_label;
                        $data[1][$k]['total'] = 0;
                        $data[1][$k]['avg'] = 0.00;
                    }
                }
            } else {
                foreach($scores as $k => $score) {
                    if($k < 36) {
                        $data[0][$k]['id'] = $score->id;
                        $data[0][$k]['student_id'] = $score->student->id;
                        $data[0][$k]['name'] = $score->student->first_name . ' ' . $score->student->last_name;
                        $data[0][$k]['gender'] = $score->student->gender_label;
                        $data[0][$k]['total'] = 0;
                        foreach($score->scoreDetails as $k2 => $detail) {
                            $data[0][$k]['total'] +=  $detail->score;
                        }
                        $data[0][$k]['avg'] =  $data[0][$k]['total'] / count($score->scoreDetails);

                    } else {
                        $data[1][$k]['id'] = $score->id;
                        $data[1][$k]['student_id'] = $score->student->id;
                        $data[1][$k]['name'] = $score->student->first_name . ' ' . $score->student->last_name;
                        $data[1][$k]['gender'] = $score->student->gender_label;
                        $data[1][$k]['total'] = 0;
                        foreach($score->scoreDetails as $k1 => $detail) {
                            $data[1][$k]['total'] +=  $detail->score;
                        }
                        $data[1][$k]['avg'] =  $data[1][$k]['total'] / count($score->scoreDetails);
                    }
                }
            }
        }

        for($i = 0; $i < 2; $i++) {
            $scores = [
                [
                    'avg' => null,
                    'key' => null,
                ]
            ];

            foreach($data[$i] as $k => $item) {
                $scores[$k]['avg'] = $item['avg'];
                $scores[$k]['key'] = $k;
            }

            arsort($scores);

            $scores = array_values($scores);

            $rank = 1;
            $prev_rank = $rank;
            foreach($scores as $k => $score) {
                if($k < 1 ) {
                    if(isset($data[$i][$score['key']])) {
                        $data[$i][$score['key']]['rank'] = $rank;
                        $data[$i][$score['key']]['grade'] = calcGrade($data[$i][$score['key']]['avg']);
                    }
                }  else {
                    if($scores[$k]['avg'] != $scores[$k-1]['avg']) {
                        $rank++;
                        $prev_rank = $rank;
                        $data[$i][$score['key']]['rank'] = $rank;
                        $data[$i][$score['key']]['grade'] = calcGrade($data[$i][$score['key']]['avg']);
                    } else {
                        $rank++;
                        $data[$i][$score['key']]['rank'] = $prev_rank;
                        $data[$i][$score['key']]['grade'] = calcGrade($data[$i][$score['key']]['avg']);
                    }
                }
            }

            for ($k = 0; $k < 35; $k++) {
                if(!isset($data[$i][$k])) {
                    $data[$i][$k] = [];
                }
            }
       }


        return response()->json([
            'data' => $data,
            'semester' => $request->semester ?? 1,
            'month_text' => filterMonth($request->type)
        ]);

    }


}
