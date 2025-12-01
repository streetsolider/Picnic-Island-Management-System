<?php

use App\Livewire\Forms\StaffLoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public StaffLoginForm $form;

    /**
     * Handle an incoming authentication request for staff.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('staff.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-common.auth-session-status class="mb-4" :status="session('status')" />

    <!-- Staff Login Header -->
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Staff Login</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Access your staff dashboard</p>
    </div>

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-forms.input-label for="email" :value="__('Email')" />
            <x-forms.text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-forms.input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-forms.input-label for="password" :value="__('Password')" />

            <x-forms.text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-forms.input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center justify-end mt-4 space-y-3">
            <x-admin.button.primary type="submit" class="w-full justify-center" type="submit">
                {{ __('Log in') }}
            </x-admin.button.primary>

            {{-- Guest login temporarily disabled --}}
            {{-- <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                <span>Not a staff member? </span>
                <a class="underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}" wire:navigate>
                    Guest Login
                </a>
            </div> --}}
        </div>
    </form>
</div>