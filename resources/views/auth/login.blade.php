<x-guest-layout>
    {{-- Encabezado --}}
    <div class="mb-10">
        <h2 class="text-[32px] font-extrabold text-slate-900 tracking-tight mb-2">Bienvenido de nuevo</h2>
        <p class="text-slate-500 text-sm font-medium">Autentícate para acceder al panel administrativo.</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
        @csrf

        {{-- Input: Correo Institucional --}}
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-slate-800">Correo institucional</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#005c48] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       placeholder="usuario@conalepjalisco.edu.mx" 
                       class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-300 focus:border-[#005c48] focus:ring-1 focus:ring-[#005c48] transition-all shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Input: Contraseña --}}
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-slate-800">Contraseña</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#005c48] hover:text-[#003d2f] transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#005c48] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" 
                       :type="showPassword ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••••••" 
                       class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm placeholder:text-slate-300 focus:border-[#005c48] focus:ring-1 focus:ring-[#005c48] transition-all shadow-sm">
                
                {{-- Botón Ojo Mostrar/Ocultar --}}
                <button type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.134 10.134 0 012.122-.363c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Checkbox: Recordarme --}}
        <div class="flex items-center pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember" 
                       class="w-5 h-5 border border-slate-300 rounded text-[#005c48] focus:ring-[#005c48] focus:ring-offset-0 cursor-pointer transition-colors">
                <span class="ml-3 text-sm font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Mantener sesión iniciada</span>
            </label>
        </div>

        {{-- Botón de Acción --}}
        <div class="pt-6">
            <button type="submit" class="w-full bg-[#005c48] hover:bg-[#004737] text-white font-semibold text-sm py-4 px-4 rounded-xl shadow-lg shadow-[#005c48]/20 transition-all duration-200 flex items-center justify-center gap-2 group">
                Ingresar al sistema
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>