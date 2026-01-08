<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\Room;
use App\Models\DamageReport;
use App\Models\Unit;
use App\Models\InventoryLog;
use App\Models\ProcurementRequest;
use App\Models\AcademicYear;
use App\Imports\InventoryImport;
use App\Exports\InventoryTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SarprasController extends Controller
{
    private function logAction($inventory_id, $action, $details = null)
    {
        InventoryLog::create([
            'inventory_id' => $inventory_id,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $details
        ]);
    }

    public function inventoryHistory(Inventory $inventory)
    {
        $history = $inventory->full_history;
        return response()->json([
            'success' => true,
            'inventory' => $inventory,
            'history' => $history
        ]);
    }
    // ================== CATEGORIES ==================
    public function categories(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('inventory_categories')) {
            return back()->with('error', 'Database belum siap.');
        }

        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();

        $allowedUnits = Auth::user()->getSarprasUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $unit_id = $request->get('unit_id');
        
        // If unit selected, check if it's allowed. If not selected AND not admin, default to first allowed.
        if ($unit_id) {
            if (!in_array($unit_id, $allowedUnitIds)) {
                $unit_id = $allowedUnitIds[0] ?? null;
            }
        } else {
            if (Auth::user()->role !== 'administrator' && Auth::user()->role !== 'direktur') {
                $unit_id = $allowedUnitIds[0] ?? null;
            }
        }

        // Default to active year if not specified in request (fresh load)
        $academic_year_id = $request->has('academic_year_id') 
            ? $request->get('academic_year_id') 
            : ($activeAcademicYear ? $activeAcademicYear->id : null);

        // Filter Categories Counts
        $categoriesQuery = InventoryCategory::query();
        
        if ($unit_id) {
            $categoriesQuery->where(function($q) use ($unit_id) {
                $q->where('unit_id', $unit_id)
                  ->orWhereNull('unit_id');
            });
        } else {
            $categoriesQuery->where(function($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds)
                  ->orWhereNull('unit_id');
            });
        }

        if ($academic_year_id) {
            $categoriesQuery->where('academic_year_id', $academic_year_id);
        }

        $categories = $categoriesQuery->orderBy('name')
            ->withCount([
            'inventories' => function ($query) use ($unit_id, $academic_year_id, $allowedUnitIds) {
                if ($unit_id) {
                    $query->whereHas('room', function ($q) use ($unit_id) {
                        $q->where('unit_id', $unit_id);
                    });
                } else {
                    $query->whereHas('room', function ($q) use ($allowedUnitIds) {
                        $q->whereIn('unit_id', $allowedUnitIds);
                    });
                }

                if ($academic_year_id) {
                    $query->whereHas('room', function ($q) use ($academic_year_id) {
                        $q->where('academic_year_id', $academic_year_id);
                    });
                }
            },
            'consumables' => function ($query) use ($unit_id, $allowedUnitIds) {
                if ($unit_id) {
                    $query->where('unit_id', $unit_id);
                } else {
                    $query->whereIn('unit_id', $allowedUnitIds);
                }
            }
        ])->get();

        // Filter Room Types Counts
        $roomTypesQuery = \App\Models\RoomType::query();
        
        if ($unit_id) {
            $roomTypesQuery->where('unit_id', $unit_id);
        } else {
            $roomTypesQuery->whereIn('unit_id', $allowedUnitIds);
        }

        if ($academic_year_id) {
            $roomTypesQuery->where('academic_year_id', $academic_year_id);
        }

        $roomTypes = $roomTypesQuery->orderBy('name')
            ->withCount(['rooms' => function ($query) use ($unit_id, $academic_year_id, $allowedUnitIds) {
                if ($unit_id) {
                    $query->where('unit_id', $unit_id);
                } else {
                    $query->whereIn('unit_id', $allowedUnitIds);
                }

                if ($academic_year_id) {
                    $query->where('academic_year_id', $academic_year_id);
                }
            }])
            ->get();

        // Data for Filters (Only show allowed units)
        $units = $allowedUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('sarpras.categories.index', compact('categories', 'roomTypes', 'units', 'academicYears', 'unit_id', 'academic_year_id', 'activeAcademicYear'));
    }

    public function storeCategory(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->firstOrFail();
        
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('inventory_categories')->where(function ($query) use ($request, $activeYear) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->where('academic_year_id', $activeYear->id);
                }),
            ],
            'unit_id' => 'nullable|exists:units,id',
            'is_consumable' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['academic_year_id'] = $activeYear->id;
        $data['is_consumable'] = $request->has('is_consumable');
        
        InventoryCategory::create($data);
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyCategory(InventoryCategory $category)
    {
        if ($category->inventories()->exists() || $category->consumables()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh data barang.');
        }
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function storeRoomType(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->firstOrFail();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('room_types')->where(function ($query) use ($request, $activeYear) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->where('academic_year_id', $activeYear->id);
                }),
            ],
            'label' => 'required|string|max:255',
            'color' => 'required|string',
            'unit_id' => 'required|exists:units,id',
        ]);
        
        $data = $request->all();
        $data['academic_year_id'] = $activeYear->id;

        \App\Models\RoomType::create($data);
        return back()->with('success', 'Tipe ruangan berhasil ditambahkan.');
    }

    public function destroyRoomType(\App\Models\RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->with('error', 'Tipe Ruangan tidak bisa dihapus karena sedang digunakan oleh data ruangan.');
        }
        $roomType->delete();
        return back()->with('success', 'Tipe Ruangan berhasil dihapus.');
    }

    public function destroyUnit(\App\Models\Unit $unit)
    {
        if ($unit->rooms()->exists()) {
            return back()->with('error', 'Unit tidak bisa dihapus karena sedang digunakan oleh data ruangan.');
        }
        $unit->delete();
        return back()->with('success', 'Unit Pendidikan berhasil dihapus.');
    }

    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'start_year' => 'required|integer|min:2000',
            'end_year' => 'required|integer|gt:start_year',
            'status' => 'required|in:active,inactive',
        ]);
        
        if ($request->status == 'active') {
            // Deactivate others
            \App\Models\AcademicYear::where('status', 'active')->update(['status' => 'inactive']);
        }
        
        \App\Models\AcademicYear::create($request->all());
        return back()->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    public function destroyAcademicYear(\App\Models\AcademicYear $academicYear)
    {
        if ($academicYear->rooms()->exists()) {
             return back()->with('error', 'Tahun Pelajaran tidak bisa dihapus karena sedang digunakan oleh data ruangan.');
        }
        if ($academicYear->status == 'active') {
             return back()->with('error', 'Tahun Pelajaran aktif tidak bisa dihapus.');
        }
        $academicYear->delete();
        return back()->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }

    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('inventories')) {
            return view('sarpras.index', [
                'stats' => ['total_items' => 0, 'broken_items' => 0, 'pending_reports' => 0, 'total_rooms' => 0, 'low_stock' => 0],
                'recentReports' => collect(),
                'conditionStats' => []
            ])->with('error', 'Database belum siap. Silakan jalankan php artisan migrate.');
        }

        // Get Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');

        // Queries with Unit Filter
        $inventoryQuery = Inventory::whereHas('room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        $reportQuery = DamageReport::whereHas('inventory.room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        $roomQuery = Room::whereIn('unit_id', $allowedUnitIds);

        $consumableQuery = \App\Models\Consumable::whereHas('category', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        $stats = [
            'total_items' => (clone $inventoryQuery)->count(),
            'broken_items' => (clone $inventoryQuery)->whereIn('condition', ['Damaged', 'Broken'])->count(),
            'pending_reports' => (clone $reportQuery)->where('status', 'Pending')->count(),
            'total_rooms' => $roomQuery->count(),
            'low_stock' => (clone $consumableQuery)->whereColumn('stock', '<=', 'min_stock')->count(),
        ];

        $recentReports = $reportQuery->with(['inventory', 'user'])->latest()->limit(5)->get();
        
        $conditionStats = (clone $inventoryQuery)
            ->select('condition', DB::raw('count(*) as count'))
            ->groupBy('condition')
            ->pluck('count', 'condition')
            ->toArray();

        return view('sarpras.index', compact('stats', 'recentReports', 'conditionStats'));
    }

    // ... rooms methods ...

    // ================== CONSUMABLES ==================
    public function consumables(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('consumables')) {
            return back()->with('error', 'Database belum siap. Silakan jalankan php artisan migrate.');
        }

        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $allowedUnits = Auth::user()->getSarprasUnits();
        
        $unit_id = $request->get('unit_id');
        $academic_year_id = $request->get('academic_year_id', ($activeAcademicYear ? $activeAcademicYear->id : null));

        $query = \App\Models\Consumable::with('category')
            ->whereHas('category', function($q) {
                $q->where('is_consumable', true);
            });

        // Filter by Unit (either via the consumable's unit_id OR its category's unit_id)
        if ($unit_id) {
            $query->where(function($q) use ($unit_id) {
                $q->where('unit_id', $unit_id)
                  ->orWhereHas('category', function($sq) use ($unit_id) {
                      $sq->where('unit_id', $unit_id);
                  });
            });
        } elseif (Auth::user()->role !== 'administrator' && Auth::user()->role !== 'direktur') {
            $allowedUnitIds = $allowedUnits->pluck('id')->toArray();
            $query->whereIn('unit_id', $allowedUnitIds)
                  ->orWhereHas('category', function($sq) use ($allowedUnitIds) {
                      $sq->whereIn('unit_id', $allowedUnitIds);
                  });
        }

        // Filter by Academic Year
        if ($academic_year_id) {
            $query->where(function($q) use ($academic_year_id) {
                $q->where('academic_year_id', $academic_year_id)
                  ->orWhereHas('category', function($sq) use ($academic_year_id) {
                      $sq->where('academic_year_id', $academic_year_id);
                  });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('inventory_category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $consumables = $query->paginate(20);
        
        // Filter categories list based on selected unit
        $catQuery = InventoryCategory::where('is_consumable', true);
        if ($unit_id) {
            $catQuery->where(function($q) use ($unit_id) {
                $q->where('unit_id', $unit_id)->orWhereNull('unit_id');
            });
        }
        $categories = $catQuery->get();

        $units = $allowedUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('sarpras.consumables.index', compact('consumables', 'categories', 'units', 'academicYears', 'unit_id', 'academic_year_id', 'activeAcademicYear'));
    }

    public function consumableHistory(Request $request)
    {
        $query = \App\Models\ConsumableTransaction::with(['consumable', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('consumable', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->latest()->paginate(30);
        return view('sarpras.consumables.history', compact('transactions'));
    }

    public function storeConsumable(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'unit_id' => 'required|exists:units,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'unit_name' => 'required|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        if (empty($data['academic_year_id']) && $activeYear) {
            $data['academic_year_id'] = $activeYear->id;
        }

        \App\Models\Consumable::create($data);

        return back()->with('success', 'Barang habis pakai berhasil ditambahkan.');
    }

    public function updateConsumable(Request $request, \App\Models\Consumable $consumable)
    {
        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'unit_id' => 'required|exists:units,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'unit_name' => 'required|string',
            'min_stock' => 'nullable|integer|min:0',
        ]);

        $consumable->update($request->all());

        return back()->with('success', 'Data barang berhasil diperbarui.');
    }

    public function transactConsumable(Request $request, \App\Models\Consumable $consumable)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
            'note' => 'nullable|string',
        ]);

        if ($request->type === 'out' && $consumable->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        DB::transaction(function() use ($request, $consumable) {
            \App\Models\ConsumableTransaction::create([
                'consumable_id' => $consumable->id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'type' => $request->type,
                'note' => $request->note,
            ]);

            if ($request->type === 'in') {
                $consumable->increment('stock', $request->quantity);
            } else {
                $consumable->decrement('stock', $request->quantity);
            }
        });

        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroyConsumable(\App\Models\Consumable $consumable)
    {
        $consumable->delete();
        return back()->with('success', 'Barang habis pakai berhasil dihapus.');
    }

    public function storeUnit(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:units,name']);
        $unit = Unit::create(['name' => $request->name]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'unit' => $unit]);
        }
        return back()->with('success', 'Unit berhasil ditambahkan.');
    }

    // ================== ROOMS ==================
    public function rooms(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('rooms')) {
            return back()->with('error', 'Database belum siap. Silakan jalankan php artisan migrate.');
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        // Default to active year if not specified
        if (!$request->filled('academic_year_id') && $activeYear) {
            $request->merge(['academic_year_id' => $activeYear->id]);
        }

        $allowedUnits = Auth::user()->getSarprasUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $unitId = $request->get('unit_id');
        
        // Force evaluation of unit access
        if ($unitId) {
            if (!in_array($unitId, $allowedUnitIds)) {
                $unitId = $allowedUnitIds[0] ?? null;
            }
        } else {
            if (Auth::user()->role !== 'administrator' && Auth::user()->role !== 'direktur') {
                $unitId = $allowedUnitIds[0] ?? null;
            }
        }

        $query = Room::with(['unit', 'academicYear', 'inventories']);
        
        if ($unitId) {
            $query->where('unit_id', $unitId);
        } else {
            $query->whereIn('unit_id', $allowedUnitIds);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        
        $rooms = $query->paginate(15);
        $units = $allowedUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        // Fetch room types keyed by name for easy lookup
        $roomTypes = \App\Models\RoomType::all()->keyBy('name');

        return view('sarpras.rooms.index', compact('rooms', 'units', 'academicYears', 'activeYear', 'roomTypes', 'unitId'));
    }

    public function storeRoom(Request $request)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->firstOrFail();
        
        $request->merge(['academic_year_id' => $activeYear->id]);

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'person_in_charge' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        if (empty($data['person_in_charge'])) {
            $unit = \App\Models\Unit::find($request->unit_id);
            if ($unit) {
                $data['person_in_charge'] = $unit->getSarprasOfficerName();
            }
        }

        Room::create($data);

        return back()->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        if ($room->academic_year_id != $activeYear->id) {
            return back()->with('error', 'Data ini adalah arsip dan tidak dapat diubah.');
        }

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'person_in_charge' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        if (empty($data['person_in_charge'])) {
            $unit = \App\Models\Unit::find($request->unit_id);
            if ($unit) {
                $data['person_in_charge'] = $unit->getSarprasOfficerName();
            }
        }

        $room->update($data);

        return back()->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroyRoom(Room $room)
    {
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        if ($room->academic_year_id != $activeYear->id) {
            return back()->with('error', 'Data ini adalah arsip dan tidak dapat dihapus.');
        }

        $room->delete();

        return back()->with('success', 'Ruangan berhasil dihapus.');
    }

    // ================== INVENTORY ==================
    public function inventory(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('inventories')) {
            return back()->with('error', 'Database belum siap. Silakan jalankan php artisan migrate.');
        }

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if (!$request->has('academic_year_id') && $activeYear) {
            $request->merge(['academic_year_id' => $activeYear->id]);
        }

        $query = Inventory::with(['category', 'room.unit', 'room.academicYear']);

        // Filter by User's Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');
        
        // Auto-select unit if user only has 1 allowed unit OR is not Admin/Director
        $userRole = Auth::user()->role;
        $isAdminOrDirector = in_array($userRole, ['administrator', 'direktur']);

        if (!$request->filled('unit_id')) {
            if ($allowedUnitIds->count() == 1 || !$isAdminOrDirector) {
                $request->merge(['unit_id' => $allowedUnitIds->first()]);
            }
        }

        $query->whereHas('room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

    if ($request->filled('unit_id')) {
        $query->whereHas('room', function($q) use ($request) {
            $q->where('unit_id', $request->unit_id);
        });
    }
    if ($request->filled('academic_year_id')) {
        $query->whereHas('room', function($q) use ($request) {
            $q->where('academic_year_id', $request->academic_year_id);
        });
    }

    if ($request->filled('category_id')) {
        $query->where('inventory_category_id', $request->category_id);
    }
    if ($request->filled('room_id')) {
        $query->where('room_id', $request->room_id);
    }
    if ($request->filled('condition')) {
        $query->where('condition', $request->condition);
    }
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('code', 'like', '%' . $request->search . '%');
    }

    $inventories = $query->paginate(20);
    $categories = InventoryCategory::all();
    
    // Filter rooms dropdown for the main filter (flexible)
    $roomsQuery = Room::whereIn('unit_id', $allowedUnitIds); // Apply allowed units filter

    if ($request->filled('unit_id')) {
        $roomsQuery->where('unit_id', $request->unit_id);
    }
    if ($request->filled('academic_year_id')) {
        $roomsQuery->where('academic_year_id', $request->academic_year_id);
    }
    $rooms = $roomsQuery->with('unit')->get();

    // For the modal "Tambah Barang", strictly show ONLY rooms from the ACTIVE year
    // $activeYear is already fetched at the start of the function
    $activeRoomsQuery = Room::whereIn('unit_id', $allowedUnitIds); // Apply allowed units filter
    
    if ($activeYear) {
        $activeRoomsQuery->where('academic_year_id', $activeYear->id);
    } else {
        $activeRoomsQuery->whereRaw('0 = 1'); 
    }
    
    // Also filter active rooms by unit if selected, for convenience
    if ($request->filled('unit_id')) {
        $activeRoomsQuery->where('unit_id', $request->unit_id);
    }
    $activeRooms = $activeRoomsQuery->with('unit')->get();

    $units = Auth::user()->getSarprasUnits();
    $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

    // Generate a suggested code for the first row in "Input Banyak"
    $nextCode = $this->generateNextCode();

    return view('sarpras.inventory.index', compact('inventories', 'categories', 'rooms', 'nextCode', 'units', 'academicYears', 'activeRooms', 'activeYear'));
}

    public function printInventory(Request $request)
    {
        $query = Inventory::with(['category', 'room.unit', 'room.academicYear']);

        // Filter by User's Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');
        $query->whereHas('room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        if ($request->filled('unit_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }
        if ($request->filled('academic_year_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        if ($request->filled('category_id')) {
            $query->where('inventory_category_id', $request->category_id);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $inventories = $query->orderBy('room_id')->orderBy('name')->get();

        // Fetch Officials
        $officials = [
            'sarpras' => '.........................',
            'principal' => '.........................',
            'director' => '.........................',
        ];
        
        $director = \App\Models\User::where('role', 'direktur')->first();
        if ($director) $officials['director'] = $director->name;

        // Determine Unit Context logic reuse
        $unitId = $request->unit_id;
        if (!$unitId && $inventories->isNotEmpty()) {
            $firstUnitId = $inventories->first()->room->unit_id;
            $isSameUnit = $inventories->every(fn($i) => $i->room->unit_id == $firstUnitId);
            if ($isSameUnit) $unitId = $firstUnitId;
        }

        if ($unitId) {
            $principal = \App\Models\User::whereHas('jabatanUnits', function($q) use ($unitId) {
                $q->where('unit_id', $unitId)->whereHas('jabatan', function($q2) {
                    $q2->where('kode_jabatan', 'kepala_sekolah')->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%');
                });
            })->first();
            if ($principal) $officials['principal'] = $principal->name;

            $unit = \App\Models\Unit::find($unitId);
            if ($unit && $unit->getSarprasOfficerName()) {
                $officials['sarpras'] = $unit->getSarprasOfficerName();
            }
        }

        return view('sarpras.inventory.print', compact('inventories', 'officials'));
    }

    public function generateNextCode()
    {
        $lastItem = Inventory::orderBy('id', 'desc')->first();
        $number = 1;
        if ($lastItem) {
            $lastCode = $lastItem->code;
            // Match any digit sequence after IVN- or INV-
            if (preg_match('/IVN-(\d+)/', $lastCode, $matches)) {
                $number = (int)$matches[1] + 1;
            } elseif (preg_match('/INV-(\d+)/', $lastCode, $matches)) {
                $number = (int)$matches[1] + 1;
            }
        }
        return str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function printBarcodes(Request $request)
    {
        $ids = explode(',', $request->ids);
        $items = Inventory::whereIn('id', $ids)->get();
        return view('sarpras.inventory.print-barcodes', compact('items'));
    }

    public function storeInventory(Request $request)
    {
        if ($request->has('items') && is_array($request->items)) {
            $items = $request->items;
            foreach ($items as $key => $item) {
                if (isset($item['price'])) {
                    $cleanedPrice = str_replace('.', '', $item['price']);
                    $items[$key]['price'] = ($cleanedPrice === '') ? null : $cleanedPrice;
                }
                
                // Also clean purchase_date if empty
                if (isset($item['purchase_date']) && empty($item['purchase_date'])) {
                    $items[$key]['purchase_date'] = null;
                }
            }
            $request->merge(['items' => $items]);

            $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
            $activeYearId = $activeYear ? $activeYear->id : null;

            $validated = $request->validate([
                'items.*.inventory_category_id' => 'required|exists:inventory_categories,id',
                'items.*.name' => 'required|string|max:255',
                'items.*.code' => 'required|string|unique:inventories,code',
                'items.*.room_id' => [
                    'nullable',
                    \Illuminate\Validation\Rule::exists('rooms', 'id')->where(function ($query) use ($activeYearId) {
                        return $query->where('academic_year_id', $activeYearId);
                    }),
                ],
                'items.*.condition' => 'required|string',
                'items.*.purchase_date' => 'nullable|date',
                'items.*.price' => 'nullable|numeric',
                'items.*.source' => 'nullable|string|max:255',
                'items.*.person_in_charge' => 'nullable|string|max:255',
                'items.*.is_grant' => 'nullable|boolean',
                'items.*.photo' => 'nullable|image|max:2048', // JPG, PNG, etc.
            ]);

            DB::transaction(function() use (&$items, $request) {
                foreach ($items as $key => &$item) {
                     // Ensure is_grant is boolean even if not submitted
                    $item['is_grant'] = isset($item['is_grant']) && $item['is_grant'] == '1' ? true : false;
                    
                    // If PJ is empty, try to get from Room or Unit's Sarpras Officer
                    if (empty($item['person_in_charge'])) {
                        $room = \App\Models\Room::with('unit')->find($item['room_id'] ?? null);
                        if ($room) {
                            $item['person_in_charge'] = $room->person_in_charge ?: $room->unit->getSarprasOfficerName();
                        }
                    }

                    if ($request->hasFile("items.$key.photo")) {
                        $path = $request->file("items.$key.photo")->store('inventory-photos', 'public');
                        $item['photo'] = $path;
                    }
                    $inv = Inventory::create($item);
                    $this->logAction($inv->id, 'Created', 'Barang ditambahkan via Input Banyak.');
                }
            });

            return back()->with('success', count($items) . ' barang inventaris berhasil ditambahkan.');
        }

        if ($request->has('price')) {
            $request->merge(['price' => str_replace('.', '', $request->price)]);
        }

        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:inventories,code',
            'room_id' => 'nullable|exists:rooms,id',
            'price' => 'nullable|numeric',
            'source' => 'nullable|string|max:255',
            'person_in_charge' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if (empty($data['person_in_charge'])) {
            $room = \App\Models\Room::with('unit')->find($request->room_id);
            if ($room) {
                $data['person_in_charge'] = $room->person_in_charge ?: $room->unit->getSarprasOfficerName();
            }
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('inventory-photos', 'public');
        }
        
        $data['is_grant'] = $request->has('is_grant');

        $inv = Inventory::create($data);
        $this->logAction($inv->id, 'Created', 'Barang ditambahkan secara manual.');

        return back()->with('success', 'Barang inventaris berhasil ditambahkan.');
    }

    public function updateInventory(Request $request, Inventory $inventory)
    {
        if ($request->has('price')) {
            $request->merge(['price' => str_replace('.', '', $request->price)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'source' => 'nullable|string|max:255',
            'person_in_charge' => 'nullable|string|max:255',
            'is_grant' => 'nullable|boolean',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_grant'] = $request->has('is_grant');

        if (empty($data['person_in_charge'])) {
            $room = \App\Models\Room::with('unit')->find($inventory->room_id); // Using existing room if not changed
            if ($request->filled('room_id')) {
                $room = \App\Models\Room::with('unit')->find($request->room_id); // Use new room if changing
            }
            if ($room) {
                $data['person_in_charge'] = $room->person_in_charge ?: $room->unit->getSarprasOfficerName();
            }
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($inventory->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->photo);
            }
            $data['photo'] = $request->file('photo')->store('inventory-photos', 'public');
        }

        $changes = [];
        if ($request->filled('room_id') && $inventory->room_id != $request->room_id) {
            $oldRoom = $inventory->room->name ?? 'None';
            $newRoom = \App\Models\Room::find($request->room_id)->name ?? 'None';
            $changes[] = "Pindah Ruangan: {$oldRoom} -> {$newRoom}";
        }

        $inventory->update($data);
        
        if (!empty($changes)) {
            $this->logAction($inventory->id, 'Updated', implode(', ', $changes));
        } else {
            $this->logAction($inventory->id, 'Updated', 'Informasi barang diperbarui.');
        }

        return back()->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroyInventory(Inventory $inventory)
    {
        // Delete photo if exists
        if ($inventory->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->photo);
        }
        
        // Delete disposal photo if exists
        if ($inventory->disposal_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->disposal_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->disposal_photo);
        }

        // Force delete will also cascade delete logs and reports in database
        $inventory->forceDelete();
        
        return back()->with('success', 'Barang inventaris berhasil dihapus secara permanen dari database.');
    }

    public function importInventory(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'unit_id' => 'required|exists:units,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        try {
            Excel::import(new InventoryImport($request->unit_id, $request->academic_year_id), $request->file('file'));
            return back()->with('success', 'Data inventaris berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadInventoryTemplate()
    {
        return Excel::download(new InventoryTemplateExport, 'template_inventaris.xlsx');
    }

    public function disposedInventory(Request $request)
    {
        $query = Inventory::onlyTrashed()->with(['category', 'room.unit', 'room.academicYear']);

        // Filter by User's Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');
        $query->whereHas('room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        if ($request->filled('unit_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('disposal_reason', 'LIKE', "%{$search}%");
            });
        }

        $inventories = $query->latest('deleted_at')->paginate(20);
        $units = Auth::user()->getSarprasUnits();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('sarpras.inventory.disposed', compact('inventories', 'units', 'academicYears'));
    }

    public function printDisposalProof($id)
    {
        $inventory = Inventory::onlyTrashed()->with(['room.unit', 'category'])->findOrFail($id);
        
        // Find the report that caused this disposal (if any)
        $report = DamageReport::where('inventory_id', $id)
            ->whereIn('follow_up_action', ['Disposal', 'Write-off'])
            ->where('director_status', 'Approved')
            ->latest()
            ->first();

        // Fetch Officials
        $officials = [
            'sarpras' => '.........................',
            'principal' => '.........................',
            'director' => '.........................',
        ];
        
        $director = \App\Models\User::where('role', 'direktur')->first();
        if ($director) $officials['director'] = $director->name;

        $unitId = $inventory->room->unit_id ?? null;
        if ($unitId) {
            $principal = \App\Models\User::whereHas('jabatanUnits', function($q) use ($unitId) {
                $q->where('unit_id', $unitId)->whereHas('jabatan', function($q2) {
                    $q2->where('kode_jabatan', 'kepala_sekolah')->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%');
                });
            })->first();
            if ($principal) $officials['principal'] = $principal->name;

            $unit = \App\Models\Unit::find($unitId);
            if ($unit && $unit->getSarprasOfficerName()) {
                $officials['sarpras'] = $unit->getSarprasOfficerName();
            }
        }

        return view('sarpras.inventory.disposal-proof', compact('inventory', 'report', 'officials'));
    }

    public function uploadDisposalPhoto(Request $request, $id)
    {
        $request->validate([
            'disposal_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $inventory = Inventory::onlyTrashed()->findOrFail($id);

        if ($request->hasFile('disposal_photo')) {
            // Delete old one if exists
            if ($inventory->disposal_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->disposal_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->disposal_photo);
            }
            $path = $request->file('disposal_photo')->store('disposal-proofs', 'public');
            $inventory->update(['disposal_photo' => $path]);
            
            $this->logAction($inventory->id, 'Bukti Pemusnahan', 'Mengunggah foto bukti penghapusan/pemusnahan barang.');
        }

        return back()->with('success', 'Foto bukti penghapusan berhasil diunggah. Sekarang Anda dapat mencetak Berita Acara.');
    }

    public function disposeInventory(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $request->validate([
            'disposal_reason' => 'required|string|max:500'
        ]);

        $inventory->update(['disposal_reason' => $request->disposal_reason]);
        $this->logAction($inventory->id, 'Disposed', "Penghapusan/Pemusnahan barang: {$request->disposal_reason}");
        $inventory->delete();

        return back()->with('success', 'Barang berhasil dimusnahkan dan dipindahkan ke arsip.');
    }

    public function restoreInventory($id)
    {
        if (Auth::user()->role !== 'administrator') {
            abort(403, 'Hanya Administrator yang dapat memulihkan barang dari arsip.');
        }

        $inventory = Inventory::onlyTrashed()->findOrFail($id);
        $inventory->restore();
        $this->logAction($inventory->id, 'Restored', 'Barang dikembalikan dari arsip.');

        return back()->with('success', 'Data barang berhasil dikembalikan ke daftar aktif.');
    }

    public function forceDeleteInventory($id)
    {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);
        
        if ($inventory->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($inventory->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->photo);
        }

        $inventory->forceDelete();
        return back()->with('success', 'Data barang berhasil dihapus permanen.');
    }

    // ================== DAMAGE REPORTS ==================
    public function damageReports(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('damage_reports')) {
            return back()->with('error', 'Database belum siap. Silakan jalankan php artisan migrate.');
        }

        $query = DamageReport::with(['inventory.room.unit', 'inventory.room.academicYear', 'user']);

        // Filter by User's Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');
        $query->whereHas('inventory.room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        // Auto-select unit if user only has 1 allowed unit
        if ($allowedUnitIds->count() == 1 && !$request->filled('unit_id')) {
            $request->merge(['unit_id' => $allowedUnitIds->first()]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        


        if ($request->filled('unit_id')) {
            $query->whereHas('inventory.room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->whereHas('inventory.room', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        $reports = $query->latest()->paginate(20);
        $inventories = Inventory::orderBy('name')->get(['id', 'name', 'code']);
        
        $units = Auth::user()->getSarprasUnits();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('sarpras.reports.index', compact('reports', 'inventories', 'units', 'academicYears'));
    }

    public function printReports(Request $request)
    {
        $query = DamageReport::with(['inventory.room.unit', 'inventory.room.academicYear', 'user', 'principal', 'director']);

        // Filter by User's Allowed Units
        $allowedUnitIds = Auth::user()->getSarprasUnits()->pluck('id');
        $query->whereHas('inventory.room', function($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('unit_id')) {
            $query->whereHas('inventory.room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->whereHas('inventory.room', function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        $reports = $query->latest()->get();

        // Fetch Officials for Signature
        $officials = [
            'sarpras' => '.........................',
            'principal' => '.........................',
            'director' => '.........................',
        ];

        // Director (Global)
        $director = \App\Models\User::where('role', 'direktur')->first();
        if ($director) {
            $officials['director'] = $director->name;
        }

        // Determine Unit Context
        $unitId = $request->unit_id;
        if (!$unitId && $reports->isNotEmpty()) {
            // If all reports belong to the same unit, use that unit
            $firstReportUnitId = $reports->first()->inventory->room->unit_id;
            $isSameUnit = $reports->every(function ($report) use ($firstReportUnitId) {
                return $report->inventory->room->unit_id == $firstReportUnitId;
            });
            
            if ($isSameUnit) {
                $unitId = $firstReportUnitId;
            }
        }

        if ($unitId) {
            // Principal
            $principal = \App\Models\User::whereHas('jabatanUnits', function($q) use ($unitId) {
                $q->where('unit_id', $unitId)->whereHas('jabatan', function($q2) {
                    $q2->where('kode_jabatan', 'kepala_sekolah')
                       ->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%');
                });
            })->first();
            if ($principal) $officials['principal'] = $principal->name;

            // Sarpras
            $unit = \App\Models\Unit::find($unitId);
            if ($unit) {
                $sarprasName = $unit->getSarprasOfficerName();
                if ($sarprasName) $officials['sarpras'] = $sarprasName;
            }
        }

        return view('sarpras.reports.print', compact('reports', 'officials'));
    }

    public function updateReportStatus(Request $request, DamageReport $report)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processed,Fixed',
            'admin_note' => 'nullable|string',
            'type' => 'nullable|in:Damaged,Lost',
            'description' => 'nullable|string',
            'follow_up_action' => 'nullable|in:Repair,Replacement,Disposal',
            'follow_up_description' => 'nullable|string',
        ]);

        // Check if trying to proceed with execution (Processed/Fixed) but not approved by Director
        if (in_array($request->status, ['Processed', 'Fixed']) && $report->director_status !== 'Approved') {
            return back()->with('error', 'Gagal update status: Menunggu persetujuan Pimpinan Lembaga terlebih dahulu.');
        }

        $data = [
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ];

        // Allow editing core data if not yet fully approved or by admin
        if ($request->has('type')) $data['type'] = $request->type;
        if ($request->has('description')) $data['description'] = $request->description;
        if ($request->has('follow_up_action')) $data['follow_up_action'] = $request->follow_up_action;
        if ($request->has('follow_up_description')) $data['follow_up_description'] = $request->follow_up_description;

        $report->update($data);

        $this->logAction($report->inventory_id, 'Status Update', "Laporan #{$report->id} diupdate oleh Admin menjadi: {$request->status}.");

        // If FIXED, update inventory condition
        if ($request->status === 'Fixed') {
            $report->inventory->update(['condition' => 'Good']);
        } elseif ($request->status === 'Processed') {
            // Ensure condition is synced
            if ($report->type === 'Damaged') {
                $report->inventory->update(['condition' => 'Repairing']);
            } else {
                $report->inventory->update(['condition' => 'Broken']);
            }
        }

        return back()->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function destroyDamageReport(DamageReport $report)
    {
        if (Auth::user()->role !== 'administrator') {
            abort(403, 'Hanya Administrator yang dapat menghapus laporan.');
        }

        // Delete photo if exists
        if ($report->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($report->photo);
        }
        
        $report->delete();
        return back()->with('success', 'Laporan kerusakan berhasil dihapus.');
    }

    public function submitFollowUp(Request $request, DamageReport $report)
    {
        $request->validate([
            'follow_up_action' => 'required|in:Repair,Replacement,Disposal,Write-off',
            'follow_up_description' => 'required|string',
        ]);

        $report->update([
            'follow_up_action' => $request->follow_up_action,
            'follow_up_description' => $request->follow_up_description,
            'principal_approval_status' => 'Pending',
            'status' => 'Processed'
        ]);

        return back()->with('success', 'Tindak lanjut berhasil diajukan untuk approval Kepala Sekolah.');
    }

    // Unified Validation Page for Principal
    public function principalApprovals(Request $request)
    {
        $manajemenUnits = Auth::user()->getManajemenUnits();
        $unitIds = $manajemenUnits->pluck('id')->toArray();
        
        // Damage Reports
        $reportQuery = DamageReport::with(['inventory.room.unit', 'user'])
            ->whereIn('inventory_id', function($q) use ($unitIds) {
                $q->select('id')->from('inventories')->whereIn('room_id', function($sq) use ($unitIds) {
                    $sq->select('id')->from('rooms')->whereIn('unit_id', $unitIds);
                });
            })
            ->whereNotNull('follow_up_action');
            
        if ($request->filled('unit_id')) {
            $reportQuery->whereHas('inventory.room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Procurement Requests (Grouped by Batch)
        $procurementQuery = \App\Models\ProcurementRequest::with(['unit', 'user', 'category'])
            ->whereIn('unit_id', $unitIds);

        if ($request->filled('unit_id')) {
            $procurementQuery->where('unit_id', $request->unit_id);
        }

        // Group by request_code to show one entry per batch
        $procurementQuery->whereIn('id', function($q) {
            $q->selectRaw('MAX(id)')->from('procurement_requests')->groupBy('request_code');
        });

        $reports = $reportQuery->latest()->get();
        $procurements = $procurementQuery->latest()->get();

        // Append batch details
        foreach ($procurements as $p) {
            $batchItems = \App\Models\ProcurementRequest::where('request_code', $p->request_code)->get();
            $p->total_items = $batchItems->count();
            $p->total_batch_price = $batchItems->sum(function($item) {
                return $item->quantity * $item->estimated_price;
            });
            $p->batch_items = $batchItems;
        }

        $units = $manajemenUnits;

        return view('sarpras.approvals.principal', compact('reports', 'procurements', 'units'));
    }

    public function principalReports(Request $request)
    {
        return $this->principalApprovals($request);
    }

    public function approvePrincipal(Request $request, DamageReport $report)
    {
        $request->validate([
            'principal_approval_status' => 'required|in:Approved,Rejected',
            'principal_note' => 'nullable|string',
        ]);

        $report->update([
            'principal_approval_status' => $request->principal_approval_status,
            'principal_note' => $request->principal_note,
            'principal_id' => Auth::id(),
            // When validated by KS, it moves to Director Approval
            'director_status' => 'Pending' 
        ]);

        $status = $request->principal_approval_status === 'Approved' ? 'Memvalidasi' : 'Menolak';
        $this->logAction($report->inventory_id, 'Validasi KS', "Kepala Sekolah {$status} laporan #{$report->id}. Catatan: {$request->principal_note}");

        return back()->with('success', 'Validasi Kepala Sekolah berhasil disimpan.');
    }

    // Unified Approval Page for Director
    public function directorApprovals(Request $request)
    {
        // Damage Reports
        $reportQuery = DamageReport::with(['inventory.room.unit', 'principal', 'director'])
            ->where('principal_approval_status', 'Approved');

        if ($request->filled('unit_id')) {
            $reportQuery->whereHas('inventory.room', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Procurement Requests (Grouped by Validated Batches)
        $procurementQuery = \App\Models\ProcurementRequest::with(['unit', 'user', 'category'])
            ->where('principal_status', 'Validated');

        if ($request->filled('unit_id')) {
            $procurementQuery->where('unit_id', $request->unit_id);
        }

        // Group by request_code to show one entry per batch (validated items only)
        $procurementQuery->whereIn('id', function($q) {
            $q->selectRaw('MAX(id)')->from('procurement_requests')
              ->where('principal_status', 'Validated')
              ->groupBy('request_code');
        });

        $reports = $reportQuery->latest()->get();
        $procurements = $procurementQuery->latest()->get();

        // Prepare batch data (only including Validated items)
        foreach ($procurements as $p) {
            $batchItems = \App\Models\ProcurementRequest::where('request_code', $p->request_code)
                            ->where('principal_status', 'Validated')
                            ->get();
            $p->total_items = $batchItems->count();
            $p->total_original_price = $batchItems->sum(function($item) {
                return $item->quantity * $item->estimated_price;
            });
            $p->total_approved_price = $batchItems->sum(function($item) {
                // Sum items that are either Approved or still Pending (initial state)
                if ($item->director_status !== 'Rejected') {
                    $price = $item->approved_price ?: $item->estimated_price;
                    $qty = $item->approved_quantity ?: $item->quantity;
                    return (float)$qty * (float)$price;
                }
                return 0;
            });
            // Determine batch-level status for display
            if ($batchItems->every(fn($i) => $i->director_status === 'Pending')) {
                $p->director_status = 'Pending';
            } elseif ($batchItems->contains(fn($i) => $i->director_status === 'Approved')) {
                $p->director_status = 'Approved';
            } else {
                $p->director_status = 'Rejected';
            }

            // Main display price: use approved total if batch is processed, else original estimate
            $p->total_batch_price = ($p->director_status !== 'Pending') ? $p->total_approved_price : $p->total_original_price;
            $p->batch_items = $batchItems;
        }

        $units = Unit::all();

        return view('sarpras.approvals.director', compact('reports', 'procurements', 'units'));
    }

    public function directorReports(Request $request)
    {
        return $this->directorApprovals($request);
    }

    public function approveDirector(Request $request, DamageReport $report)
    {
        $request->validate([
            'director_status' => 'required|in:Approved,Rejected',
            'director_note' => 'nullable|string',
        ]);

        $report->update([
            'director_status' => $request->director_status,
            'director_note' => $request->director_note,
            'director_id' => Auth::id(),
        ]);

        $status = $request->director_status === 'Approved' ? 'Menyetujui' : 'Menolak';
        $this->logAction($report->inventory_id, 'Approval Pimpinan', "Pimpinan Lembaga {$status} (Final) laporan #{$report->id}. Catatan: {$request->director_note}");

        // If APPROVED by Director, then execute the inventory state change
        if ($request->director_status === 'Approved') {
            if ($report->follow_up_action === 'Write-off' || $report->follow_up_action === 'Disposal') {
                // Perform Actual Disposal/Soft Delete
                $inventory = $report->inventory;
                $reason = "Dihapus berdasarkan Laporan #{$report->id}. Alasan: {$report->follow_up_description}";
                
                $inventory->update(['disposal_reason' => $reason]);
                $inventory->delete(); // Soft Delete
                
                $report->update(['status' => 'Fixed']);
                
                $this->logAction($inventory->id, 'Disposed', "Pemusnahan otomatis setelah Approval Pimpinan (Laporan #{$report->id})");
            } elseif ($report->follow_up_action === 'Replacement' || $report->follow_up_action === 'Repair') {
                $report->update(['status' => 'Processed']);
                if ($report->follow_up_action === 'Replacement') {
                    $report->inventory->update(['condition' => 'Broken']);
                } else {
                    $report->inventory->update(['condition' => 'Repairing']);
                }
            }
        }

        return back()->with('success', 'Keputusan Pimpinan Lembaga berhasil disimpan.');
    }

    // ================== PROCUREMENT REQUESTS (Pengajuan Barang) ==================
    public function procurements(Request $request)
    {
        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $allowedUnits = Auth::user()->getSarprasUnits();
        
        $unit_id = $request->get('unit_id');
        $academic_year_id = $request->get('academic_year_id', ($activeAcademicYear ? $activeAcademicYear->id : null));

        $query = \App\Models\ProcurementRequest::with(['unit', 'academicYear', 'user', 'category']);

        if ($unit_id) {
            $query->where('unit_id', $unit_id);
        } elseif (Auth::user()->role !== 'administrator' && Auth::user()->role !== 'direktur') {
            $query->whereIn('unit_id', $allowedUnits->pluck('id'));
        }

        if ($academic_year_id) {
            $query->where('academic_year_id', $academic_year_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Group by request_code to show one entry per batch
        $query->whereIn('id', function($q) {
            $q->selectRaw('MAX(id)')->from('procurement_requests')->groupBy('request_code');
        });

        $procurements = $query->latest()->paginate(20);

        // Append the total items and total price for each grouped request
        foreach ($procurements as $p) {
            $batchItems = \App\Models\ProcurementRequest::where('request_code', $p->request_code)->get();
            $p->total_items = $batchItems->count();
            $p->total_original_price = $batchItems->sum(function($item) {
                return $item->quantity * $item->estimated_price;
            });
            $p->total_approved_price = $batchItems->sum(function($item) {
                // Sum items that are either Approved or still Pending (inherit estimates)
                if ($item->director_status !== 'Rejected') {
                    $price = $item->approved_price ?: $item->estimated_price;
                    $qty = $item->approved_quantity ?: $item->quantity;
                    return (float)$qty * (float)$price;
                }
                return 0;
            });

            // Recalculate batch-level director_status based on all items
            if ($batchItems->every(fn($i) => $i->director_status === 'Pending')) {
                $p->director_status = 'Pending';
            } elseif ($batchItems->contains(fn($i) => $i->director_status === 'Approved')) {
                $p->director_status = 'Approved';
            } else {
                $p->director_status = 'Rejected';
            }

            $p->total_batch_price = ($p->director_status !== 'Pending') ? $p->total_approved_price : $p->total_original_price;
            $p->batch_items = $batchItems; // Pass for detail modal
        }
        $units = $allowedUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $categories = InventoryCategory::all();

        return view('sarpras.procurements.index', compact('procurements', 'units', 'academicYears', 'categories', 'unit_id', 'academic_year_id', 'activeAcademicYear'));
    }

    public function submitProcurementReport(Request $request, \App\Models\ProcurementRequest $procurement)
    {
        $request->validate([
            'report_nota' => 'required|image|max:2048',
            'report_photo' => 'required|image|max:2048',
            'report_note' => 'nullable|string'
        ]);

        $batch = \App\Models\ProcurementRequest::where('request_code', $procurement->request_code)->get();

        $notaPath = $request->file('report_nota')->store('procurements/reports', 'public');
        $photoPath = $request->file('report_photo')->store('procurements/reports', 'public');

        foreach ($batch as $item) {
            $item->update([
                'report_nota' => $notaPath,
                'report_photo' => $photoPath,
                'report_status' => 'Reported',
                'report_at' => now(),
                'report_note' => $request->report_note
            ]);
        }

        return back()->with('success', 'Laporan realisasi berhasil dikirim ke bagian Keuangan.');
    }

    public function storeProcurement(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'activity_name' => 'required|string|max:255',
            'activity_description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:inventory_categories,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string',
            'items.*.price' => 'nullable|numeric',
            'items.*.type' => 'required|in:Asset,Consumable',
            'items.*.description' => 'nullable|string',
            'items.*.photo' => 'nullable|image|max:2048'
        ]);

        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $itemsCreated = 0;
        
        // Generate a unique Request Code for this batch using Unit Name as Prefix
        $unit = \App\Models\Unit::find($request->unit_id);
        $prefix = $unit ? str_replace(' ', '', $unit->name) : 'RQ';
        
        $lastRequest = \App\Models\ProcurementRequest::orderBy('id', 'desc')->first();
        $nextId = ($lastRequest ? $lastRequest->id : 0) + 1;
        $requestCode = strtoupper($prefix) . '-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        foreach ($request->items as $itemData) {
            $photoPath = null;
            if (isset($itemData['photo']) && $itemData['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $photoPath = $itemData['photo']->store('procurements', 'public');
            }

            \App\Models\ProcurementRequest::create([
                'request_code' => $requestCode,
                'activity_name' => $request->activity_name,
                'activity_description' => $request->activity_description,
                'unit_id' => $request->unit_id,
                'academic_year_id' => $activeYear ? $activeYear->id : null,
                'user_id' => Auth::id(),
                'inventory_category_id' => $itemData['category_id'],
                'item_name' => $itemData['name'],
                'quantity' => $itemData['quantity'],
                'unit_name' => $itemData['unit'],
                'estimated_price' => $itemData['price'] ?? 0,
                'type' => $itemData['type'],
                'description' => $itemData['description'] ?? null,
                'photo' => $photoPath,
                'status' => 'Pending',
                'principal_status' => 'Pending',
                'director_status' => 'Pending',
            ]);
            $itemsCreated++;
        }

        return back()->with('success', $itemsCreated . ' pengajuan barang berhasil dikirim dengan Kode: ' . $requestCode);
    }

    public function principalProcurements(Request $request)
    {
        $manajemenUnits = Auth::user()->getManajemenUnits();
        $unitIds = $manajemenUnits->pluck('id')->toArray();
        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        
        $query = \App\Models\ProcurementRequest::with(['unit', 'user', 'category'])
            ->whereIn('unit_id', $unitIds);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        
        $academic_year_id = $request->get('academic_year_id', ($activeAcademicYear ? $activeAcademicYear->id : null));
        if ($academic_year_id) {
            $query->where('academic_year_id', $academic_year_id);
        }

        // Group by request_code to show one entry per batch (consistent with user view)
        $query->whereIn('id', function($q) {
            $q->selectRaw('MAX(id)')->from('procurement_requests')->groupBy('request_code');
        });
        
        $procurements = $query->latest()->paginate(20);
        
        foreach ($procurements as $p) {
            $batchItems = \App\Models\ProcurementRequest::where('request_code', $p->request_code)->get();
            $p->total_items = $batchItems->count();
            $p->batch_items = $batchItems;
        }

        $units = $manajemenUnits;
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        return view('sarpras.procurements.principal-index', compact('procurements', 'units', 'academicYears', 'activeAcademicYear', 'academic_year_id'));
    }

    public function validatePrincipalProcurement(Request $request, \App\Models\ProcurementRequest $procurement)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|in:Validated,Rejected',
            'note' => 'nullable|string'
        ]);

        $inputItems = $request->input('items', []);
        $validatedCount = 0;
        $rejectedCount = 0;

        foreach ($inputItems as $itemId => $status) {
            $item = \App\Models\ProcurementRequest::find($itemId);
            // Ensure item belongs to the same batch
            if ($item && $item->request_code === $procurement->request_code) {
                $item->update([
                    'principal_status' => $status,
                    'principal_note' => $request->note,
                    'status' => $status === 'Validated' ? 'Validated' : 'Rejected',
                    'validated_at' => now()
                ]);

                if ($status === 'Validated') $validatedCount++;
                else $rejectedCount++;
            }
        }

        $msg = "Validasi selesai. {$validatedCount} item disetujui untuk diteruskan, {$rejectedCount} item ditolak.";
        return back()->with('success', $msg);
    }

    public function cancelPrincipalProcurement(Request $request, \App\Models\ProcurementRequest $procurement)
    {
        // Reset all items in the batch to Pending
        \App\Models\ProcurementRequest::where('request_code', $procurement->request_code)
            ->update([
                'principal_status' => 'Pending',
                'status' => 'Pending',
                'validated_at' => null
            ]);

        return back()->with('success', 'Validasi berhasil dibatalkan. Pengajuan kembali ke status Menunggu.');
    }

    public function directorProcurements(Request $request)
    {
        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();

        // Only show items that have been validated by Principal
        $query = \App\Models\ProcurementRequest::with(['unit', 'user', 'category'])
            ->where('principal_status', 'Validated');

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        
        $academic_year_id = $request->get('academic_year_id', ($activeAcademicYear ? $activeAcademicYear->id : null));
        if ($academic_year_id) {
            $query->where('academic_year_id', $academic_year_id);
        }

        if ($request->filled('status')) {
            $query->where('director_status', $request->status);
        }

        // Group by request_code
        $query->whereIn('id', function($q) {
            $q->selectRaw('MAX(id)')->from('procurement_requests')
              ->where('principal_status', 'Validated')
              ->groupBy('request_code');
        });

        $procurements = $query->latest()->paginate(20);

        // Prepare batch data (only including Validated items)
        foreach ($procurements as $p) {
            $batchItems = \App\Models\ProcurementRequest::where('request_code', $p->request_code)
                            ->where('principal_status', 'Validated')
                            ->get();
            $p->total_items = $batchItems->count();
            $p->total_original_price = $batchItems->sum(function($item) {
                return $item->quantity * $item->estimated_price;
            });
            $p->total_approved_price = $batchItems->sum(function($item) {
                if ($item->director_status !== 'Rejected') {
                    $price = $item->approved_price ?: $item->estimated_price;
                    $qty = $item->approved_quantity ?: $item->quantity;
                    return (float)$qty * (float)$price;
                }
                return 0;
            });
            // Determine batch-level status for display
            if ($batchItems->every(fn($i) => $i->director_status === 'Pending')) {
                $p->director_status = 'Pending';
            } elseif ($batchItems->contains(fn($i) => $i->director_status === 'Approved')) {
                $p->director_status = 'Approved';
            } else {
                $p->director_status = 'Rejected';
            }

            $p->total_batch_price = ($p->director_status !== 'Pending') ? $p->total_approved_price : $p->total_original_price;
            $p->batch_items = $batchItems;
        }

        $units = \App\Models\Unit::all();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        return view('sarpras.procurements.director-index', compact('procurements', 'units', 'academicYears', 'activeAcademicYear', 'academic_year_id'));
    }

    public function approveDirectorProcurement(Request $request, \App\Models\ProcurementRequest $procurement)
    {
        $request->validate([
            'note' => 'nullable|string',
            'items' => 'required|array'
        ]);

        $approvedCount = 0;
        $rejectedCount = 0;
        $inputItems = $request->input('items', []);

        foreach ($inputItems as $itemId => $data) {
            $item = \App\Models\ProcurementRequest::find($itemId);
            
            // Check if item belongs to the same batch and was validated by principal
            if ($item && $item->request_code === $procurement->request_code && $item->principal_status === 'Validated') {
                $status = ($data['status'] ?? 'Rejected') === 'Approved' ? 'Approved' : 'Rejected';
                
                // Fallback to existing approved values if inputs are missing (disabled fields)
                $priceInput = $data['approved_price'] ?? ($item->approved_price ?: $item->estimated_price);
                $qtyInput = $data['approved_quantity'] ?? ($item->approved_quantity ?: $item->quantity);
                
                $price = str_replace('.', '', (string)$priceInput);
                
                $item->update([
                    'director_status' => $status,
                    'director_note' => $request->note,
                    'approved_price' => $price,
                    'approved_quantity' => $qtyInput,
                    'status' => $status === 'Approved' ? 'Approved' : 'Rejected',
                    'approved_at' => now()
                ]);

                if ($status === 'Approved') $approvedCount++;
                else $rejectedCount++;
            }
        }

        $msg = "Keputusan Pimpinan disimpan. {$approvedCount} item disetujui, {$rejectedCount} item ditolak.";
        return back()->with('success', $msg);
    }

    public function updateProcurement(Request $request, \App\Models\ProcurementRequest $procurement)
    {
        if (Auth::user()->role !== 'administrator' && Auth::id() !== $procurement->user_id) {
             abort(403);
        }

        // Sanitize price inputs (strip separators)
        $items = $request->input('items', []);
        foreach ($items as &$item) {
            if (isset($item['price'])) {
                $item['price'] = str_replace('.', '', $item['price']);
            }
        }
        $request->merge(['items' => $items]);

        // Allow batch update via the main item
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'activity_name' => 'required|string|max:255',
            'activity_description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:procurement_requests,id',
            'items.*.category_id' => 'required|exists:inventory_categories,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string',
            'items.*.price' => 'nullable|numeric',
            'items.*.type' => 'required|in:Asset,Consumable',
            'items.*.description' => 'nullable|string',
            'items.*.photo' => 'nullable|image|max:2048'
        ]);

        if ($procurement->status !== 'Pending' && Auth::user()->role !== 'administrator') {
            return back()->with('error', 'Hanya pengajuan pending yang dapat diedit.');
        }

        $requestCode = $procurement->request_code;
        $batchIdsToKeep = [];

        // Retrieve separated input and file arrays
        $inputItems = $request->input('items', []);
        $fileItems = $request->file('items', []);

        foreach ($inputItems as $index => $itemData) {
            $photoPath = null;
            // Check if photo exists in the file array at the same index
            if (isset($fileItems[$index]['photo']) && $fileItems[$index]['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $photoPath = $fileItems[$index]['photo']->store('procurements', 'public');
            }

            if (!empty($itemData['id'])) {
                // Update existing item
                $existingItem = \App\Models\ProcurementRequest::find($itemData['id']);
                if ($existingItem && $existingItem->request_code === $requestCode) {
                    $updateData = [
                        'unit_id' => $request->unit_id,
                        'activity_name' => $request->activity_name,
                        'activity_description' => $request->activity_description,
                        'inventory_category_id' => $itemData['category_id'],
                        'item_name' => $itemData['name'],
                        'quantity' => $itemData['quantity'],
                        'unit_name' => $itemData['unit'],
                        'estimated_price' => $itemData['price'] ?? 0,
                        'type' => $itemData['type'],
                        'description' => $itemData['description'] ?? null,
                    ];
                    if ($photoPath) {
                        $updateData['photo'] = $photoPath;
                        // Delete old photo if exists
                        if ($existingItem->photo) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($existingItem->photo);
                        }
                    }
                    $existingItem->update($updateData);
                    $batchIdsToKeep[] = $existingItem->id;
                }
            } else {
                // Create new item in this batch
                $newItem = \App\Models\ProcurementRequest::create([
                    'request_code' => $requestCode,
                    'activity_name' => $request->activity_name,
                    'activity_description' => $request->activity_description,
                    'unit_id' => $request->unit_id,
                    'academic_year_id' => $procurement->academic_year_id,
                    'user_id' => $procurement->user_id, // Keep original owner
                    'inventory_category_id' => $itemData['category_id'],
                    'item_name' => $itemData['name'],
                    'quantity' => $itemData['quantity'],
                    'unit_name' => $itemData['unit'],
                    'estimated_price' => $itemData['price'] ?? 0,
                    'type' => $itemData['type'],
                    'description' => $itemData['description'] ?? null,
                    'photo' => $photoPath,
                    'status' => 'Pending',
                    'principal_status' => 'Pending',
                    'director_status' => 'Pending',
                ]);
                $batchIdsToKeep[] = $newItem->id;
            }
        }
        
        // Delete items that were removed from the form
        $itemsToDelete = \App\Models\ProcurementRequest::where('request_code', $requestCode)
                            ->whereNotIn('id', $batchIdsToKeep)
                            ->get();

        foreach ($itemsToDelete as $delItem) {
            if ($delItem->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($delItem->photo);
            }
            $delItem->delete();
        }

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroyProcurement(\App\Models\ProcurementRequest $procurement)
    {
        if (Auth::user()->role !== 'administrator' && Auth::id() !== $procurement->user_id) {
             abort(403);
        }

        // Administrator can always delete, others only if Pending
        if (Auth::user()->role !== 'administrator' && !in_array($procurement->report_status, ['Pending'])) {
            return back()->with('error', 'Pengajuan yang sudah diproses atau dicairkan tidak dapat dihapus oleh staf.');
        }

        DB::beginTransaction();
        try {
            // Get all items in the same batch
            $batch = \App\Models\ProcurementRequest::where('request_code', $procurement->request_code)->get();
            $requestCode = $procurement->request_code;

            foreach ($batch as $item) {
                // Delete item photo
                if ($item->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($item->photo);
                }
                // Delete report files if any
                if ($item->report_nota) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($item->report_nota);
                }
                if ($item->report_photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($item->report_photo);
                }
                $item->delete();
            }

            // Also delete related IncomeExpense record if it exists
            \App\Models\IncomeExpense::where('procurement_request_code', $requestCode)->delete();

            DB::commit();
            return back()->with('success', 'Seluruh data pengajuan (termasuk rekaman keuangan terkait) berhasil dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // ================== SCANNER ==================
    public function scan()
    {
        return view('sarpras.scan.index');
    }

    public function findByCode(Request $request)
    {
        $item = Inventory::with(['category', 'room.unit'])
            ->where('code', $request->code)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan.']);
        }

        $conditions = [
            'Good' => ['label' => 'Baik', 'color' => 'success'],
            'Repairing' => ['label' => 'Perbaikan', 'color' => 'info'],
            'Damaged' => ['label' => 'Rusak Ringan', 'color' => 'warning'],
            'Broken' => ['label' => 'Rusak Berat', 'color' => 'danger'],
        ];

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'category' => $item->category->name,
                'room' => $item->room ? "{$item->room->name} ({$item->room->unit->name})" : 'Gudang',
                'condition' => $conditions[$item->condition]['label'] ?? $item->condition,
                'condition_color' => $conditions[$item->condition]['color'] ?? 'secondary',
                'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                'purchase_date' => $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-',
                'price' => number_format($item->price, 0, ',', '.'),
                'source' => $item->source,
                'is_grant' => $item->is_grant,
                'person_in_charge' => $item->person_in_charge
            ]
        ]);
    }

    public function reportDamageByCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required|in:Damaged,Lost',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'follow_up_action' => 'required|in:Repair,Replacement,Disposal,Write-off',
            'follow_up_description' => 'required|string',
        ]);

        if (!Auth::user()->isSarpras() && !Auth::user()->isDirektur()) {
            return response()->json(['success' => false, 'message' => 'Hanya Wakil Sarpras yang dapat melaporkan kerusakan/kehilangan.']);
        }

        $item = Inventory::where('code', $request->code)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage_reports', 'public');
        }

        DamageReport::create([
            'inventory_id' => $item->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'photo' => $photoPath,
            'priority' => $request->priority,
            'follow_up_action' => $request->follow_up_action,
            'follow_up_description' => $request->follow_up_description,
            'status' => 'Pending',
            'principal_approval_status' => 'Pending',
            'director_status' => 'Pending',
        ]);

        $this->logAction($item->id, 'Pelaporan', "Melaporkan {$request->type}: {$request->description}. Usulan: {$request->follow_up_action}");

        return response()->json(['success' => true, 'message' => 'Laporan dan saran tindak lanjut berhasil dikirim ke Kepala Sekolah.']);
    }
    public function printProcurement($requestCode)
    {
        $items = \App\Models\ProcurementRequest::with(['unit', 'user', 'category'])
            ->where('request_code', $requestCode)
            ->get();

        if ($items->isEmpty()) {
            abort(404);
        }

        $mainReq = $items->first();
        
        $totalEstimated = $items->sum(function($item) {
            return $item->quantity * $item->estimated_price;
        });

        $totalApproved = $items->sum(function($item) {
            // Only sum items that are Approved by Director
            if ($item->director_status === 'Approved') {
                $p = $item->approved_price ?: $item->estimated_price;
                $q = $item->approved_quantity ?: $item->quantity;
                return $q * $p;
            }
            return 0;
        });

        // Fetch Officials for Signature
    $officials = [
        'sarpras' => '.........................',
        'principal' => '.........................',
        'director' => '.........................',
    ];
    
    $director = \App\Models\User::where('role', 'direktur')->first();
    if ($director) $officials['director'] = $director->name;

    $unitId = $mainReq->unit_id;
    if ($unitId) {
        $principal = \App\Models\User::whereHas('jabatanUnits', function($q) use ($unitId) {
            $q->where('unit_id', $unitId)->whereHas('jabatan', function($q2) {
                $q2->where('kode_jabatan', 'kepala_sekolah')->orWhere('nama_jabatan', 'LIKE', '%Kepala Sekolah%');
            });
        })->first();
        if ($principal) $officials['principal'] = $principal->name;

        $unit = \App\Models\Unit::find($unitId);
        if ($unit && $unit->getSarprasOfficerName()) {
            $officials['sarpras'] = $unit->getSarprasOfficerName();
        }
    }

    return view('sarpras.procurements.print', compact('items', 'mainReq', 'totalEstimated', 'totalApproved', 'officials'));
    }
    public function resetDirectorProcurement(\App\Models\ProcurementRequest $procurement)
    {
        // Reset only items that were Validated by Principal
        \App\Models\ProcurementRequest::where('request_code', $procurement->request_code)
            ->where('principal_status', 'Validated')
            ->update([
                'director_status' => 'Pending',
                'director_note' => null,
                'status' => 'Validated',
                'approved_at' => null,
                'approved_price' => DB::raw('estimated_price'),
                'approved_quantity' => DB::raw('quantity')
            ]);

        return back()->with('success', 'Keputusan pimpinan berhasil dibatalkan. Anda dapat mengedit kembali.');
    }
    public function resetDirectorReport(DamageReport $report)
    {
        // Check if it was Approved and if it triggered a soft-delete or state changes
        if ($report->director_status === 'Approved') {
            // Restore inventory if it was deleted
            $inventory = \App\Models\Inventory::withTrashed()->find($report->inventory_id);
            if ($inventory && $inventory->trashed()) {
                $inventory->restore();
            }
            
            // Revert condition if it was changed
            if ($report->follow_up_action === 'Replacement' || $report->follow_up_action === 'Repair') {
                $inventory->update(['condition' => 'Damaged']); // Set back to damaged
            }
        }

        $report->update([
            'director_status' => 'Pending',
            'director_note' => null,
            'director_id' => null,
            'status' => 'Pending' // Reset to initial state after principal validation
        ]);
        
        $this->logAction($report->inventory_id, 'Reset Approval Pimpinan', "Approval Pimpinan untuk laporan #{$report->id} dibatalkan.");

        return back()->with('success', 'Keputusan pimpinan untuk laporan kerusakan dibatalkan.');
    }
}
