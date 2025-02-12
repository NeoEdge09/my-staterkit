<div class="modal fade" id="{{ $page_settings['action']['view']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['view']['id_modal'] }}" aria-labelledby="viewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info-800">
                <h1 class="modal-title fs-5 text-white" id="detail_title"></h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Informasi Menu</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Nama</label>
                                    <p id="view-name" class="form-control-plaintext border-bottom"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Ikon</label>
                                    <p id="view-icon" class="form-control-plaintext border-bottom"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Rute</label>
                                    <p id="view-route" class="form-control-plaintext border-bottom"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Izin</label>
                                    <p id="view-permission" class="form-control-plaintext border-bottom"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Relasi Menu</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Menu Induk</label>
                                    <div id="view-parent" class="p-2 rounded bg-light border"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Menu Anak</label>
                                    <div id="view-children" class="list-group"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.view-item', function() {
                $('#{{ $page_settings['action']['view']['id_modal'] }}').modal('show')
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ $page_settings['action']['view']['route_show'] }}".replace(':id', id),
                    type: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        const data = response.data;
                        $('#detail_title').text("Detail " + data.name);
                        $('#view-name').text(data.name);
                        $('#view-icon').html(
                            `<span class="badge bg-light text-dark"><i class="${data.icon} me-1"></i>${data.icon}</span>`
                        );
                        $('#view-route').text(data.route || '-');
                        $('#view-permission').text(data.permission || '-');
                        $('#view-parent').html(data.parent ?
                            `<div class="d-flex align-items-center">
                                <i class="${data.parent.icon} me-2"></i>
                                <span>${data.parent.name}</span>
                            </div>` :
                            '<span class="text-muted">Tidak ada menu induk</span>'
                        );

                        const childrenContainer = $('#view-children');
                        childrenContainer.empty();

                        if (data.children && data.children.length > 0) {
                            data.children.forEach(child => {
                                childrenContainer.append(`
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <i class="${child.icon} me-2"></i>
                                            <span>${child.name}</span>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            childrenContainer.append(`
                                <div class="list-group-item text-muted">
                                    <i class="fa-regular fa-folder-open me-2"></i>
                                    Tidak ada menu anak
                                </div>
                            `);
                        }
                    }
                });
            });
        });
    </script>
@endpush
