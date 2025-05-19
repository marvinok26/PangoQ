<!-- resources/views/livewire/pages/profile/edit.blade.php -->
@extends('layouts.dashboard')
@section('title', 'Edit Profile - PangoQ')
@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Profile Settings</h1>
            <div class="mt-6 md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your account's profile information and email address.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email
                                            address</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone
                                            Number</label>
                                        <input type="text" name="phone_number" id="phone_number"
                                            value="{{ old('phone_number', $user->phone_number) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="id_card_number" class="block text-sm font-medium text-gray-700">ID Card
                                            Number</label>
                                        <input type="text" name="id_card_number" id="id_card_number"
                                            value="{{ old('id_card_number', $user->id_card_number) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('id_card_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="passport_number"
                                            class="block text-sm font-medium text-gray-700">Passport Number</label>
                                        <input type="text" name="passport_number" id="passport_number"
                                            value="{{ old('passport_number', $user->passport_number) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('passport_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of
                                            Birth</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth"
                                            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('date_of_birth')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select id="gender" name="gender"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Select gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="nationality"
                                            class="block text-sm font-medium text-gray-700">Nationality</label>
                                        <input type="text" name="nationality" id="nationality"
                                            value="{{ old('nationality', $user->nationality) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('nationality')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                        <input type="text" name="address" id="address"
                                            value="{{ old('address', $user->address) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="profile_photo" class="block text-sm font-medium text-gray-700">Profile
                                            Photo</label>

                                        <div class="mt-1 flex items-center">
                                            <div class="flex-shrink-0 h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                                                @if ($user->profile_photo)
                                                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                        alt="Profile Photo" class="h-16 w-16 object-cover">
                                                @else
                                                    <svg class="h-16 w-16 text-gray-300" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="relative bg-white rounded-md shadow-sm">
                                                    <input type="file" name="profile_photo" id="profile_photo"
                                                        class="sr-only">
                                                    <label for="profile_photo"
                                                        class="cursor-pointer px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        Change
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('profile_photo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="mt-10 md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Account Information</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your wallet and payment preferences.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('profile.account.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="account_number" class="block text-sm font-medium text-gray-700">Account
                                            Number</label>
                                        <input type="text" id="account_number"
                                            value="{{ $user->account_number ?? 'Auto-generated' }}" disabled
                                            class="mt-1 bg-gray-100 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="account_type" class="block text-sm font-medium text-gray-700">Account
                                            Type</label>
                                        <select id="account_type" name="account_type"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="personal" {{ old('account_type', $user->account_type) == 'personal' ? 'selected' : '' }}>Personal</option>
                                            <option value="business" {{ old('account_type', $user->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                        </select>
                                        @error('account_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="currency"
                                            class="block text-sm font-medium text-gray-700">Currency</label>
                                        <select id="currency" name="currency"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="AUD" {{ old('currency', $user->currency) == 'AUD' ? 'selected' : '' }}>AUD</option>
                                            <option value="KES" {{ old('currency', $user->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                            <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                        @error('currency')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="linked_bank_account"
                                            class="block text-sm font-medium text-gray-700">Linked Bank Account</label>
                                        <input type="text" name="linked_bank_account" id="linked_bank_account"
                                            value="{{ old('linked_bank_account', $user->linked_bank_account) }}"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('linked_bank_account')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="wallet_provider" class="block text-sm font-medium text-gray-700">Wallet
                                            Provider</label>
                                        <select id="wallet_provider" name="wallet_provider"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="Decircles" {{ old('wallet_provider', $user->wallet_provider) == 'Decircles' ? 'selected' : '' }}>Decircles</option>
                                            <option value="M-Pesa" {{ old('wallet_provider', $user->wallet_provider) == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                            <option value="PayPal" {{ old('wallet_provider', $user->wallet_provider) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                            <option value="Stripe" {{ old('wallet_provider', $user->wallet_provider) == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                        </select>
                                        @error('wallet_provider')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="account_status" class="block text-sm font-medium text-gray-700">Account
                                            Status</label>
                                        <select id="account_status" name="account_status"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="active" {{ old('account_status', $user->account_status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="pending" {{ old('account_status', $user->account_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        @error('account_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="preferred_payment_method"
                                            class="block text-sm font-medium text-gray-700">Preferred Payment Method</label>
                                        <select id="preferred_payment_method" name="preferred_payment_method"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="wallet" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'wallet' ? 'selected' : '' }}>Wallet
                                            </option>
                                            <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank
                                                Transfer</option>
                                            <option value="credit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'credit_card' ? 'selected' : '' }}>Credit
                                                Card</option>
                                            <option value="m_pesa" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'm_pesa' ? 'selected' : '' }}>M-Pesa
                                            </option>
                                        </select>
                                        @error('preferred_payment_method')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="daily_transaction_limit"
                                            class="block text-sm font-medium text-gray-700">Daily Transaction Limit</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" name="daily_transaction_limit" id="daily_transaction_limit"
                                                value="{{ old('daily_transaction_limit', $user->daily_transaction_limit ?? 5000) }}"
                                                min="0" step="0.01"
                                                class="pl-7 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        @error('daily_transaction_limit')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Save Account Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="mt-10 md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Delete Account</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Permanently delete your account and all associated data.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="text-sm text-gray-500">
                                <p>Once your account is deleted, all of its resources and data will be permanently deleted.
                                    Before deleting your account, please download any data or information that you wish to
                                    retain.</p>
                            </div>

                            <div class="mt-5">
                                <button type="button"
                                    onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Account
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete your account? All of your data will be permanently
                                    removed. This action cannot be undone.
                                </p>
                                <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST"
                                    class="mt-4">
                                    @csrf
                                    @method('DELETE')

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">
                                            Password
                                        </label>
                                        <input type="password" name="password" id="password" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                        <p class="mt-1 text-xs text-gray-500">Please enter your password to confirm account
                                            deletion.</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('delete-account-form').submit();"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete Account
                    </button>
                    <button type="button" onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection