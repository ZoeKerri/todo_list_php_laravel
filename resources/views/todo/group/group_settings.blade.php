@extends('layouts.app')

@section('title', 'Team Settings')

@push('styles')
<style>
    /* Header tùy chỉnh */
    .content-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }
    .content-header .title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }
    .content-header a {
        color: #fff;
        font-size: 1.2rem;
        text-decoration: none;
    }

    /* *** THAY ĐỔI CSS TẠI ĐÂY ***
      (Đã sao chép style từ trang 'group-detail' sang)
    */
    .summary-card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    .summary-box {
        background-color: #1e1e1e;
        border-radius: 12px;
        padding: 15px;
        /* Sửa từ 'text-align: center' thành flexbox */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-box .meta {
        /* (Div bọc chữ) */
    }
    .summary-box h2 {
        font-size: 2.2rem; /* Cho bự lên */
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
    
    /* Xóa .icon-box vì không dùng nữa */
    
    /* Thêm các lớp màu chữ (vì icon-box đã bị xóa) */
    .text-danger { color: #e74c3c; }
    .text-warning { color: #f39c12; }
    .text-success { color: #2ecc71; }
    /* *** KẾT THÚC THAY ĐỔI CSS *** */


    /* Thanh tìm kiếm (Giữ nguyên) */
    .search-bar {
        position: relative;
        margin: 25px 0;
    }
    .search-bar i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }
    .search-bar input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        background-color: #1e1e1e;
        border: 1px solid #333;
        border-radius: 10px;
        color: #fff;
        font-size: 1rem;
    }

    /* Bảng thành viên (Giữ nguyên) */
    .member-table {
        width: 100%;
        border-collapse: collapse;
    }
    .member-table th, .member-table td {
        padding: 12px 5px;
        text-align: left;
        font-size: 0.9rem;
    }
    .member-table th {
        color: #888;
        font-weight: 500;
    }
    .member-table td {
        color: #fff;
    }
</style>
@endpush


@section('content')

<div class="content-header">
    <a href="{{ url('/group-detail/1') }}"><i class="fas fa-arrow-left"></i></a>
    <h2 class="title">Team Summary</h2>
</div>

<h3 style="font-size: 1rem; color: #fff;">Overall Team Summary</h3>
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


<h3 style="font-size: 1rem; color: #fff; margin-top: 25px;">Summary by Member</h3>

<div class="search-bar">
    <i class="fas fa-search"></i>
    <input type="text" id="memberSearchInput" placeholder="Search Member">
</div>


<table class="member-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Completed</th>
            <th>Pending</th>
            <th>Late</th>
        </tr>
    </thead>
    
    <tbody id="memberTableBody">
        <tr>
            <td>Quang</td>
            <td>0</td>
            <td>1</td>
            <td>0</td>
        </tr>
        <tr>
            <td>QUANG2</td>
            <td>1</td>
            <td>0</td>
            <td>0</td>
        </tr>
         <tr>
            <td>Huỳnh Công Tiến</td>
            <td>2</td>
            <td>0</td>
            <td>1</td>
        </tr>
         <tr>
            <td>Thành viên B</td>
            <td>1</td>
            <td>1</td>
            <td>0</td>
        </tr>
    </tbody>
</table>

@endsection


@push('scripts')
<script>
// Đợi cho toàn bộ trang tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Lấy các phần tử cần thiết
    const searchInput = document.getElementById('memberSearchInput');
    const tableBody = document.getElementById('memberTableBody');
    const allRows = tableBody.querySelectorAll('tr'); // Lấy tất cả các hàng

    // 2. Thêm sự kiện 'keyup' (khi gõ phím) vào ô tìm kiếm
    searchInput.addEventListener('keyup', function() {
        // Lấy nội dung gõ vào, chuyển thành chữ thường, bỏ dấu cách thừa
        const filterText = searchInput.value.toLowerCase().trim();

        // 3. Lặp qua từng hàng (tr) trong bảng
        allRows.forEach(function(row) {
            
            // Lấy nội dung của ô đầu tiên (ô Tên)
            const memberNameCell = row.querySelector('td:first-child');
            
            if (memberNameCell) {
                const memberName = memberNameCell.textContent.toLowerCase();

                // 4. So sánh tên thành viên với nội dung tìm kiếm
                if (memberName.includes(filterText)) {
                    // Nếu khớp -> hiển thị hàng đó
                    row.style.display = ""; // Hiển thị
                } else {
                    // Nếu không khớp -> ẩn hàng đó
                    row.style.display = "none"; // Ẩn
                }
            }
        });
    });

});
</script>
@endpush