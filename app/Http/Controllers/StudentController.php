<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('class')) {
            $query->where('class', $request->class);
        }

        if ($request->has('search')) {
            $query->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('roll_number', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
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

        $student = Student::create($validated);

        return response()->json(['message' => 'Student registered successfully', 'data' => $student], 201);
    }

    public function show(Student $student)
    {
        $student->load(['invoices', 'payments']);
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'roll_number' => ['nullable', 'string', Rule::unique('students')->ignore($student->id)],
            'admission_date' => 'nullable|date',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $student->update($validated);

        return response()->json(['message' => 'Student updated successfully', 'data' => $student]);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
