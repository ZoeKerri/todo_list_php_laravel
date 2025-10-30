@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="account-container">

    <div class="account-header">
        <h2>Edit Profile</h2>
    </div>

    @if($errors->any())
        <x-alert type="danger" auto-hide="true" hide-delay="7000">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <div class="avatar-section">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i.imgur.com/g59A6Fp.png' }}" alt="Avatar">
        <a href="#" class="edit-icon">
            <i class="fas fa-camera"></i>
        </a>
    </div>

    <form action="{{ url('/account-info/edit') }}" method="POST">
        @csrf
        
        <div class="account-form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
        </div>
        
        <div class="account-form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <div style="margin-top: 40px;">
            <button type="submit" class="btn-account btn-primary">
                Save
            </button>
        </div>
    </form>

</div>
@endsection