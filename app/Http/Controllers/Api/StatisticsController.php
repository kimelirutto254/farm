<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Log;

use App\Models\Inspection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class StatisticsController extends Controller{
    public function getChapterData()
    {
        try {
            $chapters = DB::table('chapters')
                ->join('sub_chapter', 'chapters.id', '=', 'sub_chapter.chapter')
                ->join('requirements', 'sub_chapter.id', '=', 'requirements.subchapter')
                ->select('chapters.id as chapter_id', 'chapters.name as chapter_name', 'sub_chapter.id as subchapter_id', 'sub_chapter.name as subchapter_name', 'requirements.*')
                ->orderBy('chapter_id')
                ->orderBy('subchapter_id')
                ->get();
    
            // Now, you may want to structure the result as per your requirements.
    
            // An array to hold the structured data.
            $result = [];
    
            foreach ($chapters as $row) {
                // Append each row to the result array.
                $result[$row->chapter_id]['chapter'] = [
                    'id' => $row->chapter_id,
                    'name' => $row->chapter_name,
                ];
    
                $result[$row->chapter_id]['subchapters'][$row->subchapter_id] = [
                    'id' => $row->subchapter_id,
                    'name' => $row->subchapter_name,
                ];
    
                $result[$row->chapter_id]['requirements'][] = (array)$row;
            }
    
            return response()->json(['status' => true, 'data' => array_values($result)], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function getStatistics(Request $request)
    {
        try {
            $today = Carbon::today();
            $user = auth()->user()->id;
            $totalInspections = Inspection::where('inspector_id', $user)->count();
            $todayInspections = Inspection::where('inspector_id', $user)->whereDate('created_at', $today)->count();
            $approvedInspections = Inspection::where('inspector_id',  $user)->where('status', 'approved')->count();
            $incompleteInspections = Inspection::where('inspector_id',  $user)->where('status', 'incomplete')->count();
            $rejectedInspections = Inspection::where('inspector_id', $user)->where('status', 'rejected')->count();
            $statistics = [
                'totalInspections' => $totalInspections,
                'todayInspections' => $todayInspections,
                'approvedInspections' => $approvedInspections,
                'incompleteInspections' => $incompleteInspections,
                'rejectedInspections' => $rejectedInspections,
            ];
            
            // Convert associative array to indexed array
            $statisticsList = array_values($statistics);
            
            // Log the statistics
            \Log::info('User Inspection Statistics:', $statistics);
            
            // Return the statistics as a JSON response
            return response()->json($statisticsList);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching user inspection statistics:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
    
            return response()->json([
                'status' => false,
                'error' => [
                    'message' => 'An Error Occurred',
                    'code' => $e->getCode(),
                ],
            ], 500);
        }
    }
    
}
