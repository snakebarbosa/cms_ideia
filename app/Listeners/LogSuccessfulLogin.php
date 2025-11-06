<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
// use App\Atividade;

class LogSuccessfulLogin {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  Login  $event
	 * @return void
	 */
	public function handle(Login $event) {
		$event->user->data_last_login = date('Y-m-d H:i:s');

		// $activity = new Atividade;
		// $hist     = $activity->insertActivity($event->user->id, 'Login na GRT ', 'user');

		$event->user->save();
	}
}
