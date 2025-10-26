@extends('layouts.app')

@section('title', 'Team Summary')

@push('styles')
<style>
    /* Header tùy chỉnh (nằm trong content) */
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .content-header .title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }
    .content-header a {
        color: #fff;
        font-size: 1.2rem;
        text-decoration: none;
    }
    .content-header .icons a { margin-left: 20px; }

    
    /* * THAY ĐỔI 3 & 4: SỬA LẠI THẺ TÓM TẮT
     */
    .summary-card-grid {
        display: grid;
        /* Luôn là 3 cột */
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    .summary-box {
        background-color: #1e1e1e;
        border-radius: 12px;
        padding: 15px;
        /* Dùng flex để đẩy icon sang phải */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-box .meta {
        /* (Div bọc chữ) */
    }
    .summary-box h2 {
        font-size: 2.2rem; /* Cho bự lên 1 chút */
        margin: 0 0 5px 0;
    }
    .summary-box span {
        font-size: 0.9rem;
        color: #888;
    }
    /* Icon bên phải (bự lên) */
    .summary-box .icon-display {
        font-size: 2.5rem; 
    }
    .summary-box .text-warning { color: #f39c12; }
    .summary-box .text-danger { color: #e74c3c; }
    .summary-box .text-success { color: #2ecc71; } /* Màu xanh lá cho complete */


    /* * THAY ĐỔI 1: CSS CHO CHECKBOX TRÒN
     */
    .task-checkbox {
        position: relative;
        display: block;
        width: 25px;
        height: 25px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        flex-shrink: 0; /* Không bị co lại */
    }
    .task-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .task-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: transparent;
        border: 2px solid #888;
        border-radius: 50%;
        transition: background-color 0.2s ease;
    }
    .task-checkbox:hover input ~ .checkmark {
        background-color: #333;
    }
    /* Màu khi được tick */
    .task-checkbox input:checked ~ .checkmark {
        background-color: #6a1b9a;
        border-color: #6a1b9a;
    }
    /* Dấu tick bên trong */
    .task-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 8px;
        top: 4px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    .task-checkbox input:checked ~ .checkmark:after {
        display: block;
    }


    /* Danh sách Task (Đã sửa lại) */
    .task-list {
        background-color: #1e1e1e;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }
    .task-list-meta { display: flex; align-items: center; gap: 15px; }
    .task-list-meta .arrow {
        font-size: 1.2rem;
        color: #888;
        text-decoration: none;
    }
    
    /* * THAY ĐỔI 2: CẤU TRÚC LẠI TÊN VÀ NGÀY
     */
    .task-list-meta h4 { font-size: 1rem; font-weight: 600; margin: 0 0 4px 0; }
    .task-list-meta p { margin: 0; } /* Xóa margin mặc định */
    .task-list-meta .task-assignee {
        font-size: 0.9rem;
        color: #fff;
    }
    .task-list-meta .task-date {
        font-size: 0.85rem;
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

<div class="content-header">
    <a href="{{ url('/group') }}"><i class="fas fa-arrow-left"></i></a>
    <h2 class="title">Nhom hoc tap</h2>
    <div class="icons">
        <a href="#"><i class="fas fa-users"></i> 2</a>
        <a href="{{ url('/group-detail/1/settings') }}">
            <i class="fas fa-cog"></i>
        </a>
    </div>
</div>

<h3 style="font-size: 1rem; color: #888;">Team Summary</h3>
<div class="summary-card-grid">
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-danger">0</h2>
            <span>Pending & Late</span>
        </div>
        <i class="fas fa-exclamation-triangle text-danger icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-warning">2</h2>
            <span>Pending</span>
        </div>
        <i class="fas fa-clock text-warning icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-success">5</h2> <span>Complete</span>
        </div>
        <i class="fas fa-check-circle text-success icon-display"></i>
    </div>
</div>
<a href="#" style="color: #6a1b9a; text-decoration: none; font-size: 0.9rem;">See more</a>


<h3 style="font-size: 1rem; color: #888; margin-top: 25px;">Your Summary</h3>
<div class="summary-card-grid">
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-danger">0</h2>
            <span>Pending & Late</span>
        </div>
        <i class="fas fa-exclamation-triangle text-danger icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-warning">1</h2>
            <span>Pending</span>
        </div>
        <i class="fas fa-clock text-warning icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
            <h2 class="text-success">2</h2> <span>Complete</span>
        </div>
        <i class="fas fa-check-circle text-success icon-display"></i>
    </div>
</div>

<h3 style="font-size: 1rem; color: #888; margin-top: 25px;">Your tasks</h3>
<div class="task-list">
    <div class="task-list-meta">
        <label class="task-checkbox">
            <input type="checkbox">
            <span class="checkmark"></span>
        </label>
        
        <div>
            <h4>Lap trinh</h4>
            <p class="task-assignee">Quang</p>
            <p class="task-date"><i class="fas fa-clock"></i> 11/6/2025</p>
        </div>
    </div>
    <a href="#" class="arrow"><i class="fas fa-chevron-right"></i></a>
</div>

<h3 style="font-size: 1rem; color: #888; margin-top: 25px;">Other members' tasks</h3>
<div class="task-list">
    <div class="task-list-meta">
        <label class="task-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
        </label>
        
        <div>
            <h4>Kiem thu</h4>
            <p class="task-assignee">QUANG2</p>
            <p class="task-date"><i class="fas fa-clock"></i> 12/6/2025</p>
        </div>
    </div>
    <a href="#" class="arrow"><i class="fas fa-chevron-right"></i></a>
</div>

<a href="#" class="fab">+</a>

@endsection