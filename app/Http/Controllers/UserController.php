<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Alert;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->hasRole('superAdmin')){
                $users = User::with('roles')
                ->whereHas('roles', function($query) {
                    $query->where('name', '!=', 'Cliente');
                })
                ->get();
            
            }else{
                $users = User::with('roles')->where('id', Auth::user()->id)->get(); // Use `with` to eager load roles
            }

            return DataTables::of($users)
                ->addColumn('role', function ($row) {
                    $roles = $row->getRoleNames(); // Use getRoleNames() to get assigned roles
                    return '<span class="badge ' . $this->getRoleBadgeClass($roles->first()) . '">' . ucfirst($roles->first()) . '</span>';
                })

                ->addColumn('fecha', function ($row) {
                    return $row->created_at->format('m-d-Y'); // Use getRoleNames() to get assigned roles
    
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('usuarios.edit', $row->id);
                    $deleteUrl = route('usuarios.destroy', $row->id);
                    $pdfUrl = route('usuarios.pdf', $row->id);
                    
                    $buttons = '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Ver</a>
                                ';
                    
                    // Solo agregar el botón de eliminar si el usuario tiene el rol de superAdmin
                    if (Auth::user()->hasRole('superAdminn')) {
                        $buttons .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline;" class="btn-delete">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                     </form>';
                    }
                
                    return $buttons;
                })
                
                ->rawColumns(['role', 'actions'])
                ->make(true);
        } else {
            return view('usuarios.index');
        }
    }

    public function clientes(Request $request)
    {
        if ($request->ajax()) {
            
                $users = User::with('roles')
                ->whereHas('roles', function($query) {
                    $query->where('name', '=', 'Cliente');
                })
                ->get();
            
            

            return DataTables::of($users)
                ->addColumn('role', function ($row) {
                    $roles = $row->getRoleNames(); // Use getRoleNames() to get assigned roles
                    return '<span class="badge ' . $this->getRoleBadgeClass($roles->first()) . '">' . ucfirst($roles->first()) . '</span>';
                })

                ->addColumn('fecha', function ($row) {
                    return $row->created_at->format('m-d-Y'); // Use getRoleNames() to get assigned roles
    
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('usuarios.edit', $row->id);
                    $deleteUrl = route('usuarios.destroy', $row->id);
                    $pdfUrl = route('usuarios.pdf', $row->id);
                    
                    $buttons = '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Ver</a>
                               ';
                    
                    // Solo agregar el botón de eliminar si el usuario tiene el rol de superAdmin
                    if (Auth::user()->hasRole('superAdminn')) {
                        $buttons .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline;" class="btn-delete">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                     </form>';
                    }
                
                    return $buttons;
                })
                
                ->rawColumns(['role', 'actions'])
                ->make(true);
        } else {
            return view('usuarios.clientes');
        }
    }


    private function getRoleBadgeClass($roleName)
    {
        switch ($roleName) {
            case 'superAdmin':
                return 'bg-danger'; // Red badge
            case 'empleado':
                return 'bg-primary'; // Blue badge
            case 'cliente':
                return 'bg-success'; // Green badge
            default:
                return 'bg-secondary'; // Default badge
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los roles disponibles
        $roles = Role::all();
        $cliente = false;

        // Retornar la vista con los roles
        return view('usuarios.create', compact('roles', 'cliente'));
    }

    public function crearCliente()
    {
        // Obtener todos los roles disponibles
        $roles = Role::where('name', '!=', 'cliente')->get();

        $cliente = true;

        // Retornar la vista con los roles
        return view('usuarios.create', compact('roles', 'cliente'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            
            'dni' => 'required|string|max:20',
            'sector' => 'nullable|string|max:100',
            'calle' => 'nullable|string|max:100',
            'casa' => 'nullable|string|max:100',
             
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password ?? '12345678'), // Encriptar la contraseña
            'dni' => $request->dni,
            'sector' => $request->sector,
            'calle' => $request->calle,
            'casa' => $request->casa,
            'status' => $request->status,
        ]);

        // Asignar el rol al usuario
        if($request->role){
            $user->assignRole($request->role);

            Alert::success('¡Exito!', 'Registro hecho correctamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

            return redirect()->route('usuarios.index');
        }else{
            $user->assignRole('Cliente');

            Alert::success('¡Exito!', 'Registro hecho correctamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

            return redirect()->route('usuarios.clientes');
        }

        // Redirigir a la lista de usuarios

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Encontrar el usuario por ID
        $user = User::with('roles')->where('id',$id)->first();
        
        // Obtener todos los roles disponibles
        $roles = Role::where('name', '!=', 'cliente')->get();

        // Retornar la vista con los datos del usuario y los roles
        return view('usuarios.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'dni' => 'required|string|max:20',
            'sector' => 'nullable|string|max:100',
            'calle' => 'nullable|string|max:100',
            'casa' => 'nullable|string|max:100',
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        // Encontrar el usuario por ID
        $user = User::findOrFail($id);

        // Actualizar los campos del usuario
        $user->name = $request->name;
        $user->email = $request->email;

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->dni = $request->dni;
        $user->sector = $request->sector;
        $user->calle = $request->calle;
        $user->casa = $request->casa;
        $user->status = $request->status;

        // Guardar los cambios
        $user->save();

        // Asignar el nuevo rol al usuario
        $user->syncRoles([$request->role]);

        // Redirigir a la lista de usuarios con un mensaje de éxito
        Alert::success('¡Exito!', 'Registro actualizado correctamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

        return redirect()->route('usuarios.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(Request $request)
{
    $filters = $request->only(['fecha_inicio', 'fecha_fin']);

    return Excel::download(new UserExport($filters), 'usuarios.xlsx');
}
public function reporte(Request $request)
{
   return view('usuarios.reporte')
;}
}
