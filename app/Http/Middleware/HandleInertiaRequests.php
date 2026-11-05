<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
   
    protected $rootView = 'app';

   
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

   
        public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
           
            'auth' => [
                'user' => $user ? array_merge($user->only('id','name','email','role','photo'), [
                    'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
                ]) : null,
            ],

           
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
        ]);
    }


}
