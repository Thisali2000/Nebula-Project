<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Semester;
use App\Models\Module;
use Barryvdh\DomPDF\Facade\Pdf;

class TimetableController extends Controller
{
    // Method to show the timetable view
    public function showTimetable()
    {
        $courses = Course::all();
        $intakes = Intake::all();
        return view('timetable', compact('courses', 'intakes'));
    }

    public function store(Request $request)
    {
        // Your code to save the timetable data
    }

    public function getIntakesForCourseAndLocation($courseId, $location)
    {
        $course = \App\Models\Course::find($courseId);
        if (!$course) {
            return response()->json(['intakes' => []]);
        }
        $intakes = \App\Models\Intake::where('course_name', $course->course_name)
            ->where('location', $location)
            ->orderBy('batch')
            ->get(['intake_id', 'batch']);
        return response()->json(['intakes' => $intakes]);
    }

    // New method to get courses by location and course type
    public function getCoursesByLocation(Request $request)
    {
        $location = $request->input('location');
        $courseType = $request->input('course_type');

        if (!$location || !$courseType) {
            return response()->json(['success' => false, 'courses' => []]);
        }

        try {
            $courses = Course::where('location', $location)
                ->where('course_type', $courseType)
                ->orderBy('course_name')
                ->get(['course_id', 'course_name']);

            return response()->json([
                'success' => true,
                'courses' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'courses' => [],
                'message' => 'Error fetching courses'
            ]);
        }
    }

    // Method to get active and upcoming semesters for a course and intake
    public function getSemesters(Request $request)
    {
        $courseId = $request->input('course_id');
        $intakeId = $request->input('intake_id');

        if (!$courseId || !$intakeId) {
            return response()->json(['semesters' => []]);
        }

        try {
            // Get only active and upcoming semesters
            $semesters = Semester::where('course_id', $courseId)
                ->where('intake_id', $intakeId)
                ->whereIn('status', ['active', 'upcoming'])
                ->orderBy('start_date')
                ->get(['id', 'name', 'start_date', 'end_date', 'status']);

            $formattedSemesters = $semesters->map(function($semester) {
                return [
                    'id' => $semester->id,
                    'name' => $semester->name,
                    'start_date' => $semester->start_date,
                    'end_date' => $semester->end_date,
                    'status' => $semester->status
                ];
            });
            
            return response()->json(['semesters' => $formattedSemesters]);
        } catch (\Exception $e) {
            return response()->json(['semesters' => []]);
        }
    }

    // Method to get modules for a specific semester
    public function getModulesBySemester(Request $request)
    {
        $semesterId = $request->input('semester_id');
        
        \Log::info('getModulesBySemester called with semester_id:', ['semester_id' => $semesterId]);

        if (!$semesterId) {
            \Log::warning('No semester_id provided');
            return response()->json(['modules' => []]);
        }

        try {
            $semester = Semester::with('modules')->find($semesterId);
            
            \Log::info('Semester found:', ['semester' => $semester ? $semester->toArray() : null]);
            
            if (!$semester) {
                \Log::warning('Semester not found for ID:', ['semester_id' => $semesterId]);
                return response()->json(['modules' => []]);
            }

            $modules = $semester->modules->map(function($module) {
                return [
                    'module_id' => $module->module_id,
                    'module_code' => $module->module_code,
                    'module_name' => $module->module_name,
                    'full_name' => $module->module_name . ' (' . $module->module_code . ')'
                ];
            });

            \Log::info('Modules found for semester:', ['module_count' => $modules->count(), 'modules' => $modules->toArray()]);

            return response()->json(['modules' => $modules]);
        } catch (\Exception $e) {
            \Log::error('Error in getModulesBySemester:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['modules' => []]);
        }
    }

    // Method to download timetable as PDF
    public function downloadTimetablePDF(Request $request)
    {
        $courseType = $request->input('course_type');
        $location = $request->input('location');
        $courseId = $request->input('course_id');
        $intakeId = $request->input('intake_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $semester = $request->input('semester');

        // Validate required parameters
        if (!$courseType || !$location || !$courseId || !$intakeId || !$startDate || !$endDate) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        try {
            // Get course details
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            // Get intake details
            $intake = Intake::find($intakeId);
            if (!$intake) {
                return response()->json(['error' => 'Intake not found'], 404);
            }

            // Prepare data for PDF
            $data = [
                'courseType' => ucfirst($courseType),
                'courseName' => $course->course_name,
                'location' => $location,
                'intake' => $intake->batch,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'semester' => $semester,
                'generatedAt' => now()->format('Y-m-d H:i:s')
            ];

            // For degree programs, get semester details
            if ($courseType === 'degree' && $semester) {
                $semesterModel = Semester::find($semester);
                if ($semesterModel) {
                    $data['semesterName'] = $semesterModel->name;
                    $data['semesterStatus'] = $semesterModel->status;
                    
                    // Get modules for this semester
                    $modules = $semesterModel->modules;
                    $data['modules'] = $modules->map(function($module) {
                        return [
                            'code' => $module->module_code,
                            'name' => $module->module_name,
                            'full_name' => $module->module_name . ' (' . $module->module_code . ')'
                        ];
                    });
                }
            }

            // Generate PDF
            $pdf = PDF::loadView('pdf.timetable', $data);
            
            // Set PDF options
            $pdf->setPaper('A4', 'landscape');
            
            // Generate filename
            $filename = strtolower($courseType) . '_timetable_' . date('Y-m-d_H-i-s') . '.pdf';
            
            // Return PDF as download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error generating timetable PDF:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
}
