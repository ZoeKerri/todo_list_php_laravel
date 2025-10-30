@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="account-container">

    <div class="account-header">
        <h2>Account Settings</h2>
    </div>

    @if(session('success'))
        <x-alert type="success" auto-hide="true" hide-delay="5000">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="avatar-section">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i.imgur.com/g59A6Fp.png' }}" alt="Avatar" id="avatar-image">
        <input type="file" id="avatar-input" accept="image/*" style="display: none;">
        <a href="#" class="edit-icon" onclick="document.getElementById('avatar-input').click()">
            <i class="fas fa-camera"></i>
        </a>
    </div>

    <div class="account-form-group">
        <label>Name</label>
        <div class="info-text">
            <i class="fas fa-user"></i>
            {{ $user->full_name ?? 'N/A' }}
        </div>
    </div>
    
    <div class="account-form-group">
        <label>Email</label>
        <div class="info-text">
            <i class="fas fa-envelope"></i>
            {{ $user->email }}
        </div>
    </div>
    
    <div class="account-form-group">
        <label>Phone</label>
        <div class="info-text">
            <i class="fas fa-phone"></i>
            {{ $user->phone ?? 'N/A' }}
        </div>
    </div>

    <div style="margin-top: 40px;">
        <a href="{{ url('/account-info/edit') }}" class="btn-account btn-primary">
            <i class="fas fa-pen"></i> Update
        </a>
        <a href="{{ url('/account-info/change-password') }}" class="btn-account btn-secondary">
            <i class="fas fa-lock"></i> Change Password
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-account btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Hidden logout form -->
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarImage = document.getElementById('avatar-image');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Show loading
            avatarImage.style.opacity = '0.5';
            
            fetch('/account-info/upload-avatar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    avatarImage.src = data.avatar_url;
                    alert('Avatar updated successfully!');
                } else {
                    alert('Failed to update avatar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating avatar');
            })
            .finally(() => {
                avatarImage.style.opacity = '1';
            });
        }
    });
});
</script>
@endpush
@endsection