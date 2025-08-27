<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData;
use Illuminate\Support\Facades\DB;
use Exception;

class MasterDataController extends Controller
{
    // List all records
    public function index()
    {
        $data = MasterData::all();
        return response()->json(['success' => true, 'data' => $data]);
    }

    // Store a new record
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "id"         => 'nullable|integer|exists:master_data,id',
                "key_name"   => "required|string|max:60",
                "label_name" => "required|string|max:60",
                "type"       => "required|string|max:60",
            ]);

            DB::beginTransaction();
            
            $master = MasterData::updateOrCreate([
                'id' => $request['id']
            ], $validatedData);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data saved successfully', 'data' => $master]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to save data', 'error' => $e->getMessage()], 500);
        }
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                "key_name"   => "required|string|max:60",
                "label_name" => "required|string|max:60",
                "type"       => "required|string|max:60",
            ]);

            $master = MasterData::findOrFail($id);
            $master->update($validatedData);

            return response()->json(['success' => true, 'message' => 'Data updated successfully', 'data' => $master]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update data', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete a record
    public function destroy($id)
    {
        try {
            $master = MasterData::findOrFail($id);
            $master->delete();
            return response()->json(['success' => true, 'message' => 'Data deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete data', 'error' => $e->getMessage()], 500);
        }
    }
}
