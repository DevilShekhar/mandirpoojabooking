<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use App\Models\Temple;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class TempleRegistrationController extends Controller
{
    /**
     * Show registration form.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('frontend.temple-register', compact('countries'));
    }

    /**
     * Get states by country.
     */
    public function getStates($country)
    {
        if (!Country::whereKey($country)->exists()) {
            return response()->json(['message' => 'Country not found.'], 404);
        }

        return response()->json(
            State::where('country_id', $country)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    /**
     * Get districts by state.
     */
    public function getDistricts($state)
    {
        if (!State::whereKey($state)->exists()) {
            return response()->json(['message' => 'State not found.'], 404);
        }

        return response()->json(
            District::where('state_id', $state)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    /**
     * Get cities by district.
     */
    public function getCities($district)
    {
        if (!District::whereKey($district)->exists()) {
            return response()->json(['message' => 'District not found.'], 404);
        }

        return response()->json(
            City::where('district_id', $district)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    /**
     * Save user, temple, and their relationship atomically.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'temple_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:1000'],

            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'state_id' => [
                'required',
                'integer',
                Rule::exists('states', 'id')
                    ->where('country_id', $request->country_id),
            ],

            'district_id' => [
                'required',
                'integer',
                Rule::exists('districts', 'id')
                    ->where('state_id', $request->state_id),
            ],

            'city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')
                    ->where('district_id', $request->district_id),
            ],
        ]);

        try {
            DB::transaction(function () use ($validated) {

                /*
                 * Verify required tables and columns before saving.
                 */
                foreach (['users', 'temples', 'temple_user', 'roles'] as $table) {
                    if (!Schema::hasTable($table)) {
                        throw new \RuntimeException(
                            "Required database table '{$table}' does not exist."
                        );
                    }
                }

                foreach (['temple_id', 'user_id', 'approval_status'] as $column) {
                    if (!Schema::hasColumn('temple_user', $column)) {
                        throw new \RuntimeException(
                            "Missing column '{$column}' in temple_user table."
                        );
                    }
                }

                /*
                 * Find the existing manager role.
                 */
                $role = DB::table('roles')
                    ->where('name', 'Temple Manager')
                    ->first();

                if (!$role) {
                    throw new \RuntimeException(
                        'Temple Manager role does not exist in the roles table.'
                    );
                }

                /*
                 * 1. Create the user.
                 */
                $user = new User();
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->phone = $validated['mobile'];
                $user->password = Hash::make($validated['password']);

                if (Schema::hasColumn('users', 'role_id')) {
                    $user->role_id = $role->id;
                }

                if (Schema::hasColumn('users', 'status')) {
                    $user->status = 0;
                }

                $user->save();

                /*
                 * 2. Create the temple.
                 */
                $temple = new Temple();
                $temple->name = $validated['temple_name'];

                if (Schema::hasColumn('temples', 'slug')) {
                    $temple->slug = $this->makeUniqueSlug(
                        $validated['temple_name']
                    );
                }

                $temple->country_id = $validated['country_id'];
                $temple->state_id = $validated['state_id'];
                $temple->district_id = $validated['district_id'];
                $temple->city_id = $validated['city_id'];

                if (Schema::hasColumn('temples', 'address')) {
                    $temple->address = $validated['location'];
                } elseif (Schema::hasColumn('temples', 'location')) {
                    $temple->location = $validated['location'];
                } else {
                    throw new \RuntimeException(
                        'The temples table needs an address or location column.'
                    );
                }

                if (Schema::hasColumn('temples', 'status')) {
                    $temple->status = 0;
                }

                if (Schema::hasColumn('temples', 'is_verified')) {
                    $temple->is_verified = 0;
                }

                if (Schema::hasColumn('temples', 'temple_code')) {
                    $temple->temple_code = $this->makeUniqueTempleCode();
                }

                $temple->save();

                /*
                 * 3. Save the user-temple relationship.
                 */
                $pivot = [
                    'temple_id' => $temple->id,
                    'user_id' => $user->id,
                    'approval_status' => 'pending',
                ];

                if (Schema::hasColumn('temple_user', 'created_at')) {
                    $pivot['created_at'] = now();
                }

                if (Schema::hasColumn('temple_user', 'updated_at')) {
                    $pivot['updated_at'] = now();
                }

                DB::table('temple_user')->insert($pivot);

                /*
                 * Confirm the relationship exists before committing.
                 */
                $relationshipExists = DB::table('temple_user')
                    ->where('temple_id', $temple->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$relationshipExists) {
                    throw new \RuntimeException(
                        'The temple manager relationship was not saved.'
                    );
                }
            });

            return redirect()
                ->route('temple.register')
                ->with(
                    'success',
                    'Registration submitted successfully. Your account is pending admin approval.'
                );

        } catch (Throwable $e) {

            Log::error('Temple registration failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->with(
                    'error',
                    'Registration failed. Please check storage/logs/laravel.log for the exact error.'
                );
        }
    }

    /**
     * Create a unique temple slug.
     */
    private function makeUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'temple';
        $slug = $base;
        $counter = 1;

        while (DB::table('temples')->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Generate a unique temple code.
     */
    private function makeUniqueTempleCode(): string
    {
        do {
            $code = 'TEM-' . strtoupper(Str::random(10));
        } while (
            DB::table('temples')->where('temple_code', $code)->exists()
        );

        return $code;
    }
}