<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    protected $studentRepo;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->studentRepo = $studentRepo;
    }

    public function index(Request $request)
    {
        return response()->json($this->studentRepo->getAll($request->all()));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'roll_number' => 'nullable|string|unique:students,roll_number',
            'admission_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['roll_number'])) {
            $validated['roll_number'] = 'RN-' . date('Y') . '-' . rand(1000, 9999);
        }

        $student = $this->studentRepo->create($validated);

        return response()->json(['message' => 'Student registered successfully', 'data' => $student], 201);
    }

    public function show($id)
    {
        return response()->json($this->studentRepo->findById($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'roll_number' => ['nullable', 'string', Rule::unique('students')->ignore($id)],
            'admission_date' => 'nullable|date',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $student = $this->studentRepo->update($id, $validated);

        return response()->json(['message' => 'Student updated successfully', 'data' => $student]);
    }

    public function destroy($id)
    {
        $this->studentRepo->delete($id);
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
