@extends('layouts.app')

@section('title', 'Your Groups')

@push('styles')
<style>
    /* Header màu tím */
    .group-header {
        background-color: #6a1b9a;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .group-header .icon {
        font-size: 1.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 8px;
    }
    .group-header h2, .group-header p {
        margin: 0;
    }
    .group-header h2 {
        font-size: 1.5rem;
    }
    .group-header p {
        font-size: 0.9rem;
        color: #ddd;
    }
    
    /* Danh sách nhóm */
    .group-list h3 {
        font-size: 1.2rem;
        color: #fff;
        margin-bottom: 15px;
    }
    .group-item {
        background-color: #1e1e1e;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: #fff;
        transition: background-color 0.2s ease;
    }
    .group-item:hover {
        background-color: #2c2c2c;
    }
    .group-item-meta {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .group-item-meta .avatar {
        width: 40px;
        height: 40px;
        background-color: #333;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .group-item-meta h4, .group-item-meta p {
        margin: 0;
    }
    .group-item-meta h4 {
        font-size: 1rem;
        font-weight: 600;
    }
    .group-item-meta p {
        font-size: 0.85rem;
        color: #888;
    }
    .group-item .arrow {
        font-size: 1.2rem;
        color: #888;
    }
    
    /* Nút Thêm (FAB) */
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #6a1b9a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }
</style>
@endpush


@section('content')

<div class="group-header">
    <div class="icon">H</div> <div>
        <p>Hi there, Huỳnh Công Tiến</p>
        <h2>Your groups</h2>
    </div>
</div>

<div class="group-list">
    <h3>Leader of Teams</h3>
    
    <a href="{{ url('/group-detail/1') }}" class="group-item">
        <div class="group-item-meta">
            <div class="avatar"><i class="fas fa-users"></i></div>
            <div>
                <h4>Nhom hoc tap</h4>
                <p>Huỳnh Công Tiến</p>
            </div>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </a>

    <a href="{{ url('/group-detail/2') }}" class="group-item">
        <div class="group-item-meta">
            <div class="avatar"><i class="fas fa-users"></i></div>
            <div>
                <h4>Nhom Hoc Tap</h4>
                <p>Huỳnh Công Tiến</p>
            </div>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </a>
</div>

<a href="#" class="fab">
    <i class="fas fa-plus"></i>
</a>

@endsection