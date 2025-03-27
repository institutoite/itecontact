<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Events\Auth\Authenticated;
use Illuminate\Support\Facades\Auth;



class RedirectAfterFilamentLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event)
    {
        $user = $event->user;
        
        if ($user->hasRole('admin')) { // Asegúrate de tener configurado el sistema de roles
            return redirect()->intended('/admin'); // Ruta del panel de Filament
        }
        
        return redirect()->route('home'); // Ruta para usuarios no administradores
    }
}
