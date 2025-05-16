@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Update Profile Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Update Profile Information') }}</h2>
                <p class="text-sm text-gray-600 mb-6">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
                <div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Update Password') }}</h2>
                <p class="text-sm text-gray-600 mb-6">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
                <div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Delete Account') }}</h2>
                <p class="text-sm text-gray-600 mb-6">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
