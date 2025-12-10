<?php

use App\Models\Guest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.visitor')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:guests'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($guest = Guest::create($validated)));

        Auth::login($guest);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Background Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 -z-10"></div>

    {{-- Decorative Blobs --}}
    <div class="absolute top-20 left-10 w-72 h-72 bg-brand-accent/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute top-40 right-10 w-72 h-72 bg-brand-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-brand-secondary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

    <div class="max-w-md w-full space-y-8 relative z-10" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl shadow-brand-primary/10 p-8 md:p-10 border border-gray-100"
             x-show="show"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/kabohera-logo.png') }}" alt="Kabohera Fun Island" class="h-20 w-auto">
                </div>
                <h2 class="text-3xl font-display font-bold text-brand-dark mb-2">Create Account</h2>
                <p class="text-gray-600">Begin your island adventure today</p>
            </div>

            <form wire:submit="register" class="space-y-5">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                        class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none"
                        placeholder="John Doe">
                    <x-forms.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                        class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none"
                        placeholder="you@example.com">
                    <x-forms.input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none"
                        placeholder="••••••••">
                    <x-forms.input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none"
                        placeholder="••••••••">
                    <x-forms.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- Terms & Privacy Notice --}}
                <div class="text-xs text-gray-500 text-center">
                    By creating an account, you agree to our
                    <a href="#" class="text-brand-primary hover:text-brand-primary/80">Terms of Service</a>
                    and
                    <a href="#" class="text-brand-primary hover:text-brand-primary/80">Privacy Policy</a>
                </div>

                {{-- Register Button --}}
                <button type="submit"
                    class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary hover:from-brand-primary/90 hover:to-brand-secondary/90 text-white font-semibold py-3.5 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg shadow-brand-primary/30 flex items-center justify-center gap-2">
                    <span>Create Account</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500 font-medium">Or continue with</span>
                </div>
            </div>

            {{-- Google Sign Up Button --}}
            <a href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center gap-3 px-4 py-3.5 border border-gray-200 rounded-xl font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all hover:shadow-md">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Sign up with Google</span>
            </a>

            {{-- Login Link --}}
            <div class="text-center pt-6 border-t border-gray-100 mt-6">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" wire:navigate
                            class="font-semibold text-brand-primary hover:text-brand-primary/80 transition-colors">
                            Sign in
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>