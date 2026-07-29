<?php /** @var array $payload */ $payload = $payload ?? []; ?>

<!-- ── Page header ── -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <h2 class="h5 fw-bold mb-0" style="color:var(--crm-text)">Quản lý Khách hàng</h2>
    <button class="btn btn-primary btn-sm fw-semibold" id="openCreateBtn">
        ＋ Thêm khách hàng
    </button>
</div>

<!-- ── Search row ── -->
<div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
    <div class="search-wrap" style="flex:1;max-width:380px">
        <input id="searchInput" type="search" class="form-control form-control-sm"
            placeholder="🔍  Tìm tên, email, số điện thoại..." oninput="khiNhapTimKiem()"
            onkeydown="khiNhanPhimTimKiem(event)" autocomplete="off">
        <div id="suggestions"></div>
    </div>
    <span id="countBadge" style="font-size:.8125rem;color:var(--crm-muted)"></span>
</div>

<!-- ── Customer table ── -->
<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="customerTable">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th class="sortable" data-sort-key="name" onclick="sapXepCot('name')">Họ tên <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="email" onclick="sapXepCot('email')">Email <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="phone" onclick="sapXepCot('phone')">Điện thoại <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="dob" onclick="sapXepCot('dob')">Ngày sinh <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="gender" onclick="sapXepCot('gender')">Giới tính <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="member_points" onclick="sapXepCot('member_points')">Điểm <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="member_tier" onclick="sapXepCot('member_tier')">Hạng <i
                            class="sort-icon">⇅</i></th>
                    <th class="sortable" data-sort-key="created_at" onclick="sapXepCot('created_at')">Ngày tạo <i
                            class="sort-icon">⇅</i></th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="10" class="text-center py-5" style="color:var(--crm-muted)">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Đang tải dữ liệu...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination bar -->
    <div id="paginationBar" class="d-none d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2"
        style="border-top:1px solid var(--crm-border)">
        <small id="paginationInfo" style="color:var(--crm-muted)"></small>
        <nav aria-label="Phân trang">
            <ul class="pagination pagination-sm mb-0" id="paginationBtns"></ul>
        </nav>
    </div>
</div>

<!-- ── Bootstrap Toast ── -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="toast" class="toast align-items-center border-0" role="alert" aria-live="assertive">
        <div class="d-flex">
            <div class="toast-body fw-medium" id="toastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Đóng"></button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     Create / Edit Modal
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title h6 fw-bold" id="modalTitle">Thêm khách hàng</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <form id="customerForm" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="customerId">

                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-12">
                            <label class="form-label fw-medium" for="fieldName" style="font-size:.8125rem">
                                Họ và tên <span style="color:#f87171">*</span>
                            </label>
                            <input id="fieldName" class="form-control form-control-sm" type="text"
                                placeholder="Nguyễn Văn A" required>
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <label class="form-label fw-medium" for="fieldEmail" style="font-size:.8125rem">
                                Email <span style="color:#f87171">*</span>
                            </label>
                            <input id="fieldEmail" class="form-control form-control-sm" type="email"
                                placeholder="khachhang@example.com" required>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <label class="form-label fw-medium" for="fieldPassword" style="font-size:.8125rem">
                                Mật khẩu
                                <span id="passHint" class="fw-normal" style="color:var(--crm-muted)">*</span>
                            </label>
                            <input id="fieldPassword" class="form-control form-control-sm" type="password"
                                placeholder="••••••••">
                            <div id="passHintText" class="form-text">Tối thiểu 8 ký tự.</div>
                        </div>

                        <!-- Phone / DOB -->
                        <div class="col-6">
                            <label class="form-label fw-medium" for="fieldPhone" style="font-size:.8125rem">Điện
                                thoại</label>
                            <input id="fieldPhone" class="form-control form-control-sm" type="tel"
                                placeholder="0912 345 678">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium" for="fieldDob" style="font-size:.8125rem">Ngày
                                sinh</label>
                            <input id="fieldDob" class="form-control form-control-sm" type="date">
                        </div>

                        <!-- Gender / Points -->
                        <div class="col-6">
                            <label class="form-label fw-medium" for="fieldGender" style="font-size:.8125rem">Giới
                                tính</label>
                            <select id="fieldGender" class="form-select form-select-sm">
                                <option value="">— Chọn —</option>
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium" for="fieldPoints" style="font-size:.8125rem">
                                Điểm thành viên
                            </label>
                            <input id="fieldPoints" class="form-control form-control-sm" type="number" min="0"
                                placeholder="0" value="0">
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label fw-medium" for="fieldAddress" style="font-size:.8125rem">Địa
                                chỉ</label>
                            <input id="fieldAddress" class="form-control form-control-sm" type="text"
                                placeholder="123 Đường ABC, Quận 1, TP.HCM">
                        </div>
                    </div>
                </div><!-- /modal-body -->

                <div class="modal-footer" style="border-color:var(--crm-border)">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="formSubmitBtn">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     Confirm Delete Modal
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center">
            <div class="modal-body py-4">
                <div style="font-size:2rem;margin-bottom:.75rem">🗑️</div>
                <p class="mb-1" style="font-size:.875rem;color:var(--crm-muted)">
                    Bạn có chắc muốn xóa khách hàng
                </p>
                <p class="fw-semibold mb-3" id="confirmName" style="color:var(--crm-text)"></p>
                <p style="font-size:.8rem;color:var(--crm-muted)">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer justify-content-center gap-2 pt-0 border-0">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-danger btn-sm fw-semibold" id="confirmDeleteBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     Customer Detail Modal (double-click on row to open)
     Layout giống Customer Dashboard: Banner + 2×col-md-6
     ══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="detailModal" tabindex="-1"
     aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="border-color:var(--crm-border)">
                <h5 class="modal-title fw-bold" id="detailModalLabel"
                    style="color:var(--crm-text)">Chi tiết khách hàng</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-4" id="detailModalBody">
                <!-- JS renders full layout here -->
            </div>
        </div>
    </div>
</div>