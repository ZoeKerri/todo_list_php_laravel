@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="account-container">

    <div class="account-header">
        <a href="{{ url('/account-info') }}"><i class="fas fa-arrow-left"></i></a>
        <h2>Edit Profile</h2>
    </div>

    <div class="avatar-section">
        <img src="https://i.imgur.com/g59A6Fp.png" alt="Avatar">
        <a href="#" class="edit-icon">
            <i class="fas fa-camera"></i>
        </a>
    </div>

    <form action="#" method="POST">
        @csrf
        
        <div class="account-form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="Huỳnh Công Tiến">
        </div>
        
        <div class="account-form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="0966026561">
        </div>

        <div style="margin-top: 40px;">
            <button type="submit" class="btn-account btn-primary">
                Save
            </button>
        </div>
    </form>

</div>
@endsection