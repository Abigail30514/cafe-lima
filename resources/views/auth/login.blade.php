<x-guest-layout>

    <style>

        .login-wrapper {
            width: 100%;
        }

        .login-title {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-title h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .login-title p {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .login-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 1rem;
        }

        .login-button {
            min-width: 120px;
            justify-content: center;
        }

        @media (max-width: 640px) {

            .login-title h1 {
                font-size: 1.4rem;
            }

            .login-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .login-actions a {
                text-align: center;
            }

            .login-button {
                width: 100%;
                margin-left: 0 !important;
                min-height: 44px;
            }

            #email,
            #password {
                min-height: 44px;
                font-size: 16px;
            }

            .remember-row {
                margin-top: 1rem;
            }
        }

    </style>


    <div class="login-wrapper">

        <div class="login-title">

            <h1>
                Iniciar sesión
            </h1>

            <p>
                Ingresa tus credenciales para acceder al sistema.
            </p>

        </div>


        {{-- Estado de sesión --}}
        <x-auth-session-status
            class="mb-4"
            :status="session('status')"
        />


        <form
            method="POST"
            action="{{ route('login') }}"
        >
            @csrf


            {{-- Correo --}}
            <div>

                <x-input-label
                    for="email"
                    :value="__('Correo electrónico')"
                />

                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2"
                />

            </div>


            {{-- Contraseña --}}
            <div class="mt-4">

                <x-input-label
                    for="password"
                    :value="__('Contraseña')"
                />

                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />

            </div>


            {{-- Recordarme --}}
            <div class="block mt-4 remember-row">

                <label
                    for="remember_me"
                    class="inline-flex items-center"
                >

                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember"
                    >

                    <span class="ms-2 text-sm text-gray-600">
                        Recordarme
                    </span>

                </label>

            </div>


            {{-- Acciones --}}
            <div class="login-actions">

                @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        ¿Olvidaste tu contraseña?
                    </a>

                @endif


                <x-primary-button class="login-button">
                    Iniciar sesión
                </x-primary-button>

            </div>

        </form>

    </div>

</x-guest-layout>