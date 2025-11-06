<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
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
		$users = $this->userService->getAllUsers();
		$can = Auth::user()->hasRole('superadministrator');

		return view('Administrator.User.user')
			->withData($users)
			->withCan($can);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$can = Auth::user()->hasRole('superadministrator');
		$roles = $this->userService->getAllRoles();

		return view('Administrator.User.user_form', compact('roles', 'can'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
			'roles' => 'required',
		]);

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

		if ($user) {
			return redirect()->route('User.index', $user->id);
		}

		Session::flash('danger', 'Sorry a problem occurred while creating this user.');
		return redirect()->route('User.create');
	}

	/**
	 * Display the specified resource.
	 */
	public function show($id)
	{
		$data = $this->userService->getUserById($id);
		$atividades = [];
		$can = Auth::user()->hasRole('superadministrator');

		return view('Administrator.User.info', compact('data', 'atividades', 'can'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id)
	{
		$can = Auth::user()->hasRole('superadministrator');
		$user = $this->userService->getUserById($id);
		$roleUser = $this->userService->getUserRoles($id);
		$roles = $this->userService->getAllRoles();

		return view('Administrator.User.user_form', compact('roles', 'user', 'can', 'roleUser'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, $id)
	{
		$user = $this->userService->getUserById($id);

		if (!$user) {
			Session::flash('danger', 'User not found.');
			return redirect()->route('User.index');
		}

		$this->validate($request, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
			'password_atual' => 'required|string|min:6',
			'password' => 'required|string|min:6',
			'roles' => 'required',
		]);

		// Verify current password
		if (!$this->userService->verifyPassword($id, $request->password_atual)) {
			Session::flash('danger', 'Password errado.');
			return redirect()->route('User.edit', $user->id);
		}

		$password = !empty($request->password) 
			? trim($request->password) 
			: $this->userService->generateRandomPassword();

		$userData = [
			'name' => $request->name,
			'email' => $request->email,
			'password' => $password,
			'roles' => $request->roles,
		];

		$updatedUser = $this->userService->updateUser($id, $userData);

		if ($updatedUser) {
			return redirect()->route('User.index', $updatedUser->id);
		}

		Session::flash('danger', 'Sorry a problem occurred while updating this user.');
		return redirect()->route('User.edit', $user->id);
	}

	/**
	 * Bulk activate users
	 */
	public function ativarCheck(CheckRequest $ids)
	{
		$this->userService->bulkUpdateStatus($ids->check, 1);
		return redirect()->route('User.index');
	}

	/**
	 * Bulk deactivate users
	 */
	public function desativarCheck(CheckRequest $ids)
	{
		$this->userService->bulkUpdateStatus($ids->check, 0);
		return redirect()->route('User.index');
	}

	/**
	 * Update user status
	 */
	public function updateEstado($id, $estado)
	{
		$success = $this->userService->updateUserStatus($id, $estado);

		if ($success) {
			$state = $estado == 1 ? 'Activou' : 'Desactivou';
			Session::flash('success', 'Estado do Utilizador foi alterado com sucesso!');
		}

		return redirect()->route('User.index', $id);
	}

	/**
	 * Bulk delete users
	 */
	public function removerCheck(CheckRequest $ids)
	{
		$this->userService->bulkDeleteUsers($ids->check);
		return redirect()->route('User.index');
	}

	/**
	 * Remove the specified resource from storage
	 */
	public function destroy($id)
	{
		$success = $this->userService->deleteUser($id);

		if ($success) {
			Session::flash('success', 'User removido!');
		}

		return redirect()->route('User.index');
	}

	/**
	 * Reset user password to default
	 */
	public function resetPasswordUser($id)
	{
		$this->userService->resetPassword($id);
		Session::flash('success', 'Senha resetado com sucesso! Nova senha "password".');

		return redirect()->route('User.index');
	}
}
