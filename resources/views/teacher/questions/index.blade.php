{{-- resources/views/teacher/questions/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Ngân hàng câu hỏi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>❓ Ngân hàng câu hỏi</h2>
        <p>Quản lý toàn bộ câu hỏi bạn đã tạo</p>
    </div>
    <a href="{{ route('teacher.questions.create') }}" class="btn-primary">+ Tạo câu hỏi mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- Filter --}}
<div class="form-card" style="margin-bottom:16px;padding:16px 20px">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
        <select id="filterLoai" class="form-control" style="width:180px">
            <option value="">Tất cả loại</option>
            <option value="TN">Trắc nghiệm (TN)</option>
            <option value="DS">Đúng/Sai (DS)</option>
            <option value="TLS">Trả lời số (TLS)</option>
        </select>
        <select id="filterDoKho" class="form-control" style="width:160px">
            <option value="">Tất cả độ khó</option>
            <option value="De">Dễ</option>
            <option value="Trung Binh">Trung Bình</option>
            <option value="Nang Cao">Nâng Cao</option>
        </select>
        <input type="text" id="filterText" class="form-control" style="width:260px"
               placeholder="🔍 Tìm nội dung câu hỏi...">
        <span id="countResult" style="font-size:13px;color:#64748b"></span>
    </div>
</div>

@if($questions->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📝</div>
        <p>Bạn chưa có câu hỏi nào.</p>
        <a href="{{ route('teacher.questions.create') }}" class="btn-primary">Tạo câu hỏi đầu tiên</a>
    </div>
@else
    <div class="form-card">
        <table class="data-table" id="tableQuestions">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Nội dung câu hỏi</th>
                    <th width="120">Chuyên đề</th>
                    <th width="80">Loại</th>
                    <th width="100">Độ khó</th>
                    <th width="110">Ngày tạo</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $i => $q)
                <tr data-loai="{{ $q->LoaiCauHoi }}"
                    data-dokho="{{ $q->DoKho }}"
                    data-noidung="{{ strtolower($q->NoiDungCH) }}">
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ Str::limit($q->NoiDungCH, 90) }}</td>
                    <td>{{ $q->TenChuyenDe }}</td>
                    <td>
                        <span class="badge-loai badge-loai-{{ strtolower($q->LoaiCauHoi) }}">
                            {{ $q->LoaiCauHoi }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-dokho badge-{{ strtolower(str_replace(' ', '', $q->DoKho)) }}">
                            {{ $q->DoKho }}
                        </span>
                    </td>
                    <td class="text-center" style="font-size:13px;color:#64748b">
                        {{ \Carbon\Carbon::parse($q->NgayTao)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <div style="display:flex;gap:6px;justify-content:center">
                            <a href="{{ route('teacher.questions.edit', $q->MaCauHoi) }}"
                               class="btn-outline" style="padding:5px 12px;font-size:13px">✏️</a>

                            <form action="{{ route('teacher.questions.destroy', $q->MaCauHoi) }}"
                                  method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    style="padding:5px 12px;font-size:13px"
                                    onclick="return confirm('Xóa câu hỏi này?')">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>
const filterLoai  = document.getElementById('filterLoai');
const filterDoKho = document.getElementById('filterDoKho');
const filterText  = document.getElementById('filterText');
const countResult = document.getElementById('countResult');

function applyFilter() {
    const loai  = filterLoai?.value  ?? '';
    const dokho = filterDoKho?.value ?? '';
    const text  = filterText?.value.toLowerCase() ?? '';
    let visible = 0;

    document.querySelectorAll('#tableQuestions tbody tr').forEach(row => {
        const matchLoai  = !loai  || row.dataset.loai  === loai;
        const matchDoKho = !dokho || row.dataset.dokho === dokho;
        const matchText  = !text  || row.dataset.noidung.includes(text);
        const show = matchLoai && matchDoKho && matchText;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    countResult.textContent = `Hiển thị ${visible} câu hỏi`;
}

filterLoai?.addEventListener('change', applyFilter);
filterDoKho?.addEventListener('change', applyFilter);
filterText?.addEventListener('input', applyFilter);
applyFilter();
</script>
@endsection