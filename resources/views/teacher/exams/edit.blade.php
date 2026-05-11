{{-- resources/views/teacher/exams/edit.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Sửa đề thi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>✏️ {{ $exam->TenDeThi }}</h2>
        <p>Chỉnh sửa thông tin và thêm câu hỏi vào đề</p>
    </div>
    <a href="{{ route('teacher.exams.index') }}" class="btn-outline">← Quay lại</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- ── Thông tin đề ──────────────────────────────────── --}}
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">Thông tin đề thi</h3>
    <form method="POST" action="{{ route('teacher.exams.update', $exam->MaDeThi) }}">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group" style="flex:2">
                <label>Tên đề thi</label>
                <input type="text" name="tenDeThi" value="{{ $exam->TenDeThi }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Mã đề</label>
                <input type="text" name="maDe" value="{{ $exam->MaDe }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Thời gian</label>
                <select name="thoiGian" class="form-control">
                    @foreach([15,45,60,90] as $t)
                        <option value="{{ $t }}" {{ $exam->ThoiGian == $t ? 'selected' : '' }}>{{ $t }} phút</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả cấu trúc</label>
            <textarea name="cauTruc" rows="2" class="form-control">{{ $exam->CauTrucDe }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>

{{-- ── Câu hỏi hiện trong đề ─────────────────────────── --}}
<div class="form-card" style="margin-bottom:24px">
    <h3 class="section-title">
        Câu hỏi trong đề
        <span class="badge-count">{{ $cauHoiTrongDe->count() }} câu</span>
    </h3>

    @php
        $tongDiem = $cauHoiTrongDe->sum('DiemCauHoi');
        $phanI    = $cauHoiTrongDe->where('Phan','I');
        $phanII   = $cauHoiTrongDe->where('Phan','II');
        $phanIII  = $cauHoiTrongDe->where('Phan','III');
    @endphp

    {{-- Tóm tắt điểm --}}
    <div class="diem-summary">
        <div class="diem-item">
            <span class="diem-label">Phần I (TN)</span>
            <span class="diem-val">{{ $phanI->count() }} câu × 0.25 = {{ $phanI->count() * 0.25 }}đ</span>
        </div>
        <div class="diem-item">
            <span class="diem-label">Phần II (Đúng/Sai)</span>
            <span class="diem-val">{{ $phanII->count() }} câu × 1.00 = {{ $phanII->count() * 1.0 }}đ</span>
        </div>
        <div class="diem-item">
            <span class="diem-label">Phần III (TLS)</span>
            <span class="diem-val">{{ $phanIII->count() }} câu × 0.50 = {{ $phanIII->count() * 0.5 }}đ</span>
        </div>
        <div class="diem-item diem-tong">
            <span class="diem-label">Tổng điểm</span>
            <span class="diem-val">{{ number_format($tongDiem, 2) }} / 10.00đ</span>
        </div>
    </div>

    @if($cauHoiTrongDe->isEmpty())
        <div class="empty" style="padding:20px;text-align:center;color:#94a3b8">
            Chưa có câu hỏi nào trong đề. Thêm câu hỏi từ danh sách bên dưới.
        </div>
    @else
        @foreach(['I' => 'Phần I — Trắc nghiệm nhiều lựa chọn', 'II' => 'Phần II — Đúng/Sai', 'III' => 'Phần III — Trả lời ngắn số'] as $phan => $tenPhan)
            @php $cauHoiPhan = $cauHoiTrongDe->where('Phan', $phan); @endphp
            @if($cauHoiPhan->isNotEmpty())
                <div class="phan-header">{{ $tenPhan }}</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="50">STT</th>
                            <th>Nội dung câu hỏi</th>
                            <th width="100">Chuyên đề</th>
                            <th width="80">Độ khó</th>
                            <th width="60">Điểm</th>
                            <th width="80">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cauHoiPhan->sortBy('ThuTu') as $cq)
                        <tr>
                            <td class="text-center">{{ $cq->ThuTu }}</td>
                            <td>{{ Str::limit($cq->NoiDungCH, 80) }}</td>
                            <td>{{ $cq->TenChuyenDe }}</td>
                            <td>
                                <span class="badge-dokho badge-{{ strtolower(str_replace(' ','',$cq->DoKho)) }}">
                                    {{ $cq->DoKho }}
                                </span>
                            </td>
                            <td class="text-center">{{ number_format($cq->DiemCauHoi, 2) }}</td>
                            <td class="text-center">
                                <form action="{{ route('teacher.exams.removeQuestion', $exam->MaDeThi) }}"
                                      method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="maCauHoi" value="{{ $cq->MaCauHoi }}">
                                    <button type="submit" class="btn-icon btn-danger-sm"
                                        title="Xóa khỏi đề"
                                        onclick="return confirm('Xóa câu hỏi này khỏi đề?')">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif
</div>

{{-- ── Thêm câu hỏi vào đề ──────────────────────────── --}}
<div class="form-card">
    <h3 class="section-title">
        Thêm câu hỏi vào đề
        <span class="badge-count">{{ $cauHoiCoThe->count() }} câu chưa thêm</span>
    </h3>

    {{-- Filter --}}
    <div class="filter-bar" style="margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap">
        <select id="filterLoai" class="form-control" style="width:160px">
            <option value="">Tất cả loại</option>
            <option value="TN">Trắc nghiệm (TN)</option>
            <option value="DS">Đúng/Sai (DS)</option>
            <option value="TLS">Trả lời số (TLS)</option>
        </select>
        <input type="text" id="filterText" class="form-control" style="width:260px"
               placeholder="🔍 Tìm kiếm nội dung câu hỏi...">
    </div>

    @if($cauHoiCoThe->isEmpty())
        <div class="empty" style="padding:20px;text-align:center;color:#94a3b8">
            Tất cả câu hỏi của bạn đã được thêm vào đề, hoặc bạn chưa tạo câu hỏi nào.
        </div>
    @else
        <table class="data-table" id="tableCoThe">
            <thead>
                <tr>
                    <th>Nội dung câu hỏi</th>
                    <th width="100">Chuyên đề</th>
                    <th width="70">Loại</th>
                    <th width="80">Độ khó</th>
                    <th width="130">Thêm vào phần</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cauHoiCoThe as $q)
                <tr data-loai="{{ $q->LoaiCauHoi }}" data-noidung="{{ strtolower($q->NoiDungCH) }}">
                    <td>{{ Str::limit($q->NoiDungCH, 90) }}</td>
                    <td>{{ $q->TenChuyenDe }}</td>
                    <td>
                        <span class="badge-loai badge-loai-{{ strtolower($q->LoaiCauHoi) }}">
                            {{ $q->LoaiCauHoi }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-dokho badge-{{ strtolower(str_replace(' ','',$q->DoKho)) }}">
                            {{ $q->DoKho }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('teacher.exams.addQuestion', $exam->MaDeThi) }}"
                              method="POST" style="display:flex;gap:6px;align-items:center">
                            @csrf
                            <input type="hidden" name="maCauHoi" value="{{ $q->MaCauHoi }}">
                            <select name="phan" class="form-control" style="width:70px;padding:4px 6px;font-size:13px">
                                @if($q->LoaiCauHoi === 'TN')  <option value="I">I</option> @endif
                                @if($q->LoaiCauHoi === 'DS')  <option value="II">II</option> @endif
                                @if($q->LoaiCauHoi === 'TLS') <option value="III">III</option> @endif
                            </select>
                            <button type="submit" class="btn-primary btn-sm">+ Thêm</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
// Filter câu hỏi chưa thêm
const filterLoai = document.getElementById('filterLoai');
const filterText = document.getElementById('filterText');

function applyFilter() {
    const loai = filterLoai?.value.toLowerCase() ?? '';
    const text = filterText?.value.toLowerCase() ?? '';
    document.querySelectorAll('#tableCoThe tbody tr').forEach(row => {
        const matchLoai = !loai || row.dataset.loai?.toLowerCase() === loai;
        const matchText = !text || row.dataset.noidung?.includes(text);
        row.style.display = matchLoai && matchText ? '' : 'none';
    });
}

filterLoai?.addEventListener('change', applyFilter);
filterText?.addEventListener('input', applyFilter);
</script>
@endsection