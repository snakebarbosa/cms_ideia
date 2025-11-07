<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\UpdatePasswordRequest;
use App\Http\Requests\Administrator\UserRequest;
use App\Services\UserService;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
	protected $userService;

	public function __construct(UserService $userService)
	{
		$this->middleware('auth');
		$this->userService = $userService;
	}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		try {
			$users = $this->userService->getAllUsers();
			$can = Auth::user()->hasRole('superadministrator');

			return view('Administrator.User.user')
				->withData($users)
				->withCan($can);
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao carregar utilizadores: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		try {
			$can = Auth::user()->hasRole('superadministrator');
			$roles = $this->userService->getAllRoles();

			return view('Administrator.User.user_form', compact('roles', 'can'));
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao carregar formulário: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(UserRequest $request)
	{
		try {
			$password = !empty($request->password) 
				? trim($request->password) 
				: $this->userService->generateRandomPassword();

			$userData = [
				'name' => $request->name,
				'email' => $request->email,
				'password' => $password,
				'roles' => $request->roles,
			];

			$user = $this->userService->createUser($userData);

			Session::flash('success', 'Utilizador criado com sucesso!');
			return redirect()->route('User.index', $user->id);
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao criar utilizador: ' . $e->getMessage());
			return redirect()->route('User.create')->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show($id)
	{
		try {
			$data = $this->userService->getUserById($id);
			
			if (!$data) {
				Session::flash('danger', 'Utilizador não encontrado.');
				return redirect()->route('User.index');
			}
			
			// Get user activity logs
			try {
				$atividades = \Spatie\Activitylog\Models\Activity::where('causer_id', $id)
					->where('causer_type', 'App\User')
					->orderBy('created_at', 'desc')
					->get();
			} catch (\Exception $e) {
				\Log::warning("Activity logs not available: " . $e->getMessage());
				$atividades = collect([]);
			}
			
			// Get counts of artigos and documentos created by the user
			try {
				$artigosCount = \App\Model\Artigo::where('idUser', $id)->count();
			} catch (\Exception $e) {
				\Log::warning("Could not count artigos: " . $e->getMessage());
				$artigosCount = 0;
			}
			
			try {
				$documentosCount = \App\Model\Documento::where('idUser', $id)->count();
			} catch (\Exception $e) {
				\Log::warning("Could not count documentos: " . $e->getMessage());
				$documentosCount = 0;
			}
			
			// Get monthly statistics for the last 12 months
			$monthlyStats = [];
			try {
				for ($i = 11; $i >= 0; $i--) {
					$month = \Carbon\Carbon::now()->subMonths($i);
					$monthlyStats[] = [
						'month' => $month->format('M Y'),
						'artigos' => \App\Model\Artigo::where('idUser', $id)
							->whereYear('created_at', $month->year)
							->whereMonth('created_at', $month->month)
							->count(),
						'documentos' => \App\Model\Documento::where('idUser', $id)
							->whereYear('created_at', $month->year)
							->whereMonth('created_at', $month->month)
							->count(),
					];
				}
			} catch (\Exception $e) {
				\Log::warning("Could not generate monthly stats: " . $e->getMessage());
				$monthlyStats = [];
			}
			
			$can = Auth::user()->hasRole('superadministrator');

			return view('Administrator.User.info', compact('data', 'atividades', 'can', 'artigosCount', 'documentosCount', 'monthlyStats'));
		} catch (\Exception $e) {
			\Log::error("Error in User show method: " . $e->getMessage());
			\Log::error($e->getTraceAsString());
			Session::flash('danger', 'Erro ao carregar utilizador: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id)
	{
		try {
			$can = Auth::user()->hasRole('superadministrator');
			$user = $this->userService->getUserById($id);
			
			if (!$user) {
				Session::flash('danger', 'Utilizador não encontrado.');
				return redirect()->route('User.index');
			}
			
			$roleUser = $this->userService->getUserRoles($id);
			$roles = $this->userService->getAllRoles();

			return view('Administrator.User.user_form', compact('roles', 'user', 'can', 'roleUser'));
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao carregar formulário: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UserRequest $request, $id)
	{
		try {
			$user = $this->userService->getUserById($id);

			if (!$user) {
				Session::flash('danger', 'Utilizador não encontrado.');
				return redirect()->route('User.index');
			}

			$userData = [
				'name' => $request->name,
				'email' => $request->email,
				'roles' => $request->roles,
			];

			$updatedUser = $this->userService->updateUser($id, $userData);

			Session::flash('success', 'Utilizador atualizado com sucesso!');
			return redirect()->route('User.index', $updatedUser->id);
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao atualizar utilizador: ' . $e->getMessage());
			return redirect()->route('User.edit', $id)->withInput();
		}
	}

	/**
	 * Bulk activate users
	 */
	public function ativarCheck(CheckRequest $ids)
	{
		try {
			$this->userService->bulkUpdateStatus($ids->check, 1);
			Session::flash('success', 'Utilizadores ativados com sucesso!');
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao ativar utilizadores: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Bulk deactivate users
	 */
	public function desativarCheck(CheckRequest $ids)
	{
		try {
			$this->userService->bulkUpdateStatus($ids->check, 0);
			Session::flash('success', 'Utilizadores desativados com sucesso!');
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao desativar utilizadores: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Update user status
	 */
	public function updateEstado($id, $estado)
	{
		try {
			$this->userService->updateUserStatus($id, $estado);
			$state = $estado == 1 ? 'ativado' : 'desativado';
			Session::flash('success', "Utilizador {$state} com sucesso!");
			return redirect()->route('User.index', $id);
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao alterar estado: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Bulk delete users
	 */
	public function removerCheck(CheckRequest $ids)
	{
		try {
			$this->userService->bulkDeleteUsers($ids->check);
			Session::flash('success', 'Utilizadores removidos com sucesso!');
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao remover utilizadores: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Remove the specified resource from storage
	 */
	public function destroy($id)
	{
		try {
			$this->userService->deleteUser($id);
			Session::flash('success', 'Utilizador removido com sucesso!');
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao remover utilizador: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Reset user password to default
	 */
	public function resetPasswordUser($id)
	{
		try {
			$this->userService->resetPassword($id);
			Session::flash('success', 'Senha resetada com sucesso! Nova senha "password".');
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao resetar senha: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Show the password change form
	 */
	public function showPasswordForm($id)
	{
		try {
			$user = $this->userService->getUserById($id);
			
			if (!$user) {
				Session::flash('danger', 'Utilizador não encontrado.');
				return redirect()->route('User.index');
			}

			// Check authorization: must be super admin or the user himself
			$currentUser = Auth::user();
			$isSuperAdmin = $currentUser->hasRole('superadministrator');
			$isOwnProfile = $currentUser->id == $id;

			if (!$isSuperAdmin && !$isOwnProfile) {
				Session::flash('danger', 'Não tem permissão para alterar a password deste utilizador.');
				return redirect()->route('User.index');
			}

			return view('Administrator.User.password_form', compact('user', 'isSuperAdmin'));
		} catch (\Exception $e) {
			Session::flash('danger', 'Erro ao carregar formulário: ' . $e->getMessage());
			return redirect()->route('User.index');
		}
	}

	/**
	 * Update user password
	 */
	public function updatePassword(UpdatePasswordRequest $request, $id)
	{
		try {
			$user = $this->userService->getUserById($id);
			
			if (!$user) {
				Session::flash('danger', 'Utilizador não encontrado.');
				return redirect()->route('User.index');
			}

			// Check authorization: must be super admin or the user himself
			$currentUser = Auth::user();
			$isSuperAdmin = $currentUser->hasRole('superadministrator');
			$isOwnProfile = $currentUser->id == $id;

			if (!$isSuperAdmin && !$isOwnProfile) {
				Session::flash('danger', 'Não tem permissão para alterar a password deste utilizador.');
				return redirect()->route('User.index');
			}

			$this->userService->updatePassword(
				$id, 
				$request->current_password, 
				$request->new_password
			);

			Session::flash('success', 'Password atualizada com sucesso!');
			
			// Redirect to user index or profile depending on who made the change
			if ($isOwnProfile && !$isSuperAdmin) {
				return redirect()->route('User.show', $id);
			}
			
			return redirect()->route('User.index');
		} catch (\Exception $e) {
			if (strpos($e->getMessage(), 'incorrect') !== false) {
				Session::flash('danger', 'Password atual está incorreta.');
			} else {
				Session::flash('danger', 'Erro ao atualizar password: ' . $e->getMessage());
			}
			return redirect()->route('User.password.form', $id)->withInput();
		}
	}
}
