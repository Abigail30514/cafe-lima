<x-guest-layout>

    <style>

        .forgot-wrapper {
            width: 100%;
        }

        .forgot-title {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .forgot-title h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .forgot-title p {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .forgot-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .forgot-button {
            justify-content: center;
        }

        .back-login {
            display: inline-block;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #6b7280;
            text-decoration: underline;
        }

        @media (max-width: 640px) {

            .forgot-title h1 {
                font-size: 1.4rem;
            }

            .forgot-title p {
                font-size: 0.9rem;
            }

            #email {
                min-height: 44px;
                font-size: 16px;
            }

            .forgot-actions {
                display: block;
            }

            .forgot-button {
                width: 100%;
                min-height: 44px;
            }

            .back-login {
                display: block;
                text-align: center;
                margin-top: 1rem;
            }
        }

    </style>


    <div class="forgot-wrapper">

        <div class="forgot-title">

            <h1>
                Recuperar contraseña
            </h1>

            <p>
                Ingresa tu correo electrónico y te enviaremos un enlace
                para restablecer tu contraseña.
            </p>

        </div>


        {{-- Estado de sesión --}}
        <x-auth-session-status
            class="mb-4"
            :status="session('status')"
        />


        <form
            method="POST"
            action="{{ route('password.email') }}"
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
                    autocomplete="email"
                />

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2"
                />

            </div>


            {{-- Acción --}}
            <div class="forgot-actions">

                <x-primary-button class="forgot-button">
                    Enviar enlace de recuperación
                </x-primary-button>

            </div>

        </form>


        <a
            href="{{ route('login') }}"
            class="back-login"
        >
            Volver al inicio de sesión
        </a>

    </div>

</x-guest-layout>