<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * عرض قائمة الموظفين
     */
    public function index()
    {
        $staff = User::with(['roles', 'restaurant'])
            ->where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::all();

        return view('admin.staff.index', compact('staff', 'roles'));
    }

    /**
     * صفحة إضافة موظف
     */
    public function create()
    {
        $roles = Role::all();
        $restaurants = Restaurant::orderBy('name_ar')->get();
        return view('admin.staff.create', compact('roles', 'restaurants'));
    }

    /**
     * حفظ موظف جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_active' => true,
            'created_by' => Auth::id(),
            'restaurant_id' => $validated['restaurant_id'] ?? null,
        ]);

        $role = Role::find($validated['role_id']);
        $newUser->assignRole($role);

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    /**
     * صفحة تعديل موظف
     */
    public function edit(User $staff)
    {
        $roles = Role::all();
        $restaurants = Restaurant::orderBy('name_ar')->get();
        $currentRole = $staff->roles()->first();

        return view('admin.staff.edit', compact('staff', 'roles', 'restaurants', 'currentRole'));
    }

    /**
     * تحديث موظف
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone = $validated['phone'] ?? null;
        $staff->restaurant_id = $validated['restaurant_id'] ?? null;
        $staff->is_active = $request->has('is_active');

        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        $staff->save();

        // تحديث الدور
        $staff->roles()->detach();
        $role = Role::find($validated['role_id']);
        $staff->assignRole($role);

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    /**
     * حذف موظف
     */
    public function destroy(User $staff)
    {
        if ($staff->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك');
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }

    /**
     * تفعيل/تعطيل موظف
     */
    public function toggleStatus(User $staff)
    {
        $staff->is_active = !$staff->is_active;
        $staff->save();

        $status = $staff->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} الموظف بنجاح");
    }
}