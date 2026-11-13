<x-admin-layout>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Two-Factor Authentication Setup') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- 2FA Status Section -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Two Factor Authentication Status</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    @if($is2faEnabled)
                                        <span class="text-green-600 font-semibold">Enabled</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Disabled</span>
                                    @endif
                                </p>
                            </div>
                            @if($is2faEnabled)
                                <button onclick="document.getElementById('disable2fa').style.display='block'" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Disable 2FA
                                </button>
                            @endif
                        </div>
                    </div>

                    @if(!$is2faEnabled)
                        <!-- Setup Section -->
                        <div class="mb-4">
                            <p class="mb-4">Scan this QR code with your Google Authenticator app:</p>
                            <div class="mt-4 w-48 h-48">
                                {!! $qrCode !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Or manually enter this code in your app:</p>
                            <p class="font-mono mt-2 text-lg">{{ $secret }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.2fa.confirm') }}" class="mt-6">
                            @csrf
                            <div>
                                <x-input-label for="code" :value="__('Verification Code')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-primary-button>
                                    {{ __('Confirm & Enable 2FA') }}
                                </x-primary-button>
                            </div>
                        </form>

                        <div class="mt-8">
                            <h3 class="font-semibold text-lg mb-4">Recovery Codes</h3>
                            <p class="mb-4 text-sm text-gray-600">Store these recovery codes in a secure place. They can be used to recover access to your account if you lose your 2FA device.</p>
                            <div class="bg-gray-100 p-4 rounded">
                                @foreach($recoveryCodes as $code)
                                    <div class="font-mono">{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Disable 2FA Modal -->
                    <div id="disable2fa" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Disable 2FA</h3>
                                <div class="mt-2 px-7 py-3">
                                    <form method="POST" action="{{ route('admin.2fa.disable') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="code" class="block text-sm font-medium text-gray-700">Enter your current 2FA code to confirm</label>
                                            <x-text-input type="text" name="code" id="code" class="block mt-1 w-full" required autofocus />
                                            @error('code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="document.getElementById('disable2fa').style.display='none'" class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300">Cancel</button>
                                            <button type="submit" class="bg-red-500 px-4 py-2 rounded-md text-sm font-medium text-white hover:bg-red-600">Disable</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>