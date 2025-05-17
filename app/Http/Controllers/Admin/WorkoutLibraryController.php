<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkoutCategory;
use App\Models\WorkoutType;

class WorkoutLibraryController extends Controller
{
    public function index()
    {
        $categories = WorkoutCategory::with('types')->get();
        return view('admin.workout-library', compact('categories'));
    }

    public function addCategory(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        WorkoutCategory::create(['name' => $request->name]);
        return back()->with('success', 'Category added successfully.');
    }

    public function addType(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:workout_categories,id',
            'name' => 'required|string'
        ]);
        WorkoutType::create([
            'workout_category_id' => $request->category_id,
            'name' => $request->name,
        ]);
        return back()->with('success', 'Workout type added successfully.');
    }


// 🔁 Update Category
public function updateCategory(Request $request, $id)
{
    $request->validate(['name' => 'required|string|max:255']);
    WorkoutCategory::findOrFail($id)->update(['name' => $request->name]);
    return back()->with('success', 'Category updated successfully.');
}

// ❌ Delete Category
public function deleteCategory($id)
{
    WorkoutCategory::findOrFail($id)->delete();
    return back()->with('success', 'Category deleted.');
}

// 🔁 Update Type
public function updateType(Request $request, $id)
{
    $request->validate(['name' => 'required|string|max:255']);
    WorkoutType::findOrFail($id)->update(['name' => $request->name]);
    return back()->with('success', 'Type updated successfully.');
}

// ❌ Delete Type
public function deleteType($id)
{
    WorkoutType::findOrFail($id)->delete();
    return back()->with('success', 'Type deleted.');
}
}
