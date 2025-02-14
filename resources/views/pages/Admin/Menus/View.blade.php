<div class="modal fade" id="{{ $page_settings['action']['view']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['view']['id_modal'] }}" aria-labelledby="viewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info-800">
                <h1 class="modal-title fs-5 text-white">
                    <i class="ti ti-eye me-2"></i><span id="detail_title"></span>
                </h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Menu Information Card -->
                    <div class="col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="ti ti-file-description me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Menu Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted small">Menu Name</label>
                                    <p id="view-name" class="form-control-plaintext border-bottom pb-2 fs-6"></p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted small">Icon</label>
                                    <p id="view-icon" class="form-control-plaintext border-bottom pb-2"></p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted small">Route</label>
                                    <p id="view-route" class="form-control-plaintext border-bottom pb-2"></p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted small">Permission</label>
                                    <p id="view-permission" class="form-control-plaintext border-bottom pb-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Relations Card -->
                    <div class="col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="ti ti-hierarchy me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Menu Relations</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted small">Parent Menu</label>
                                    <div id="view-parent" class="p-3 rounded bg-light border"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Child Menus</label>
                                    <div id="view-children" class="list-group border rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Close
                </button>
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
                        $('#detail_title').text("Menu Details: " + data.name);
                        $('#view-name').text(data.name);
                        $('#view-icon').html(
                            `<span class="badge bg-light border text-dark px-3 py-2">
                                <i class="${data.icon} me-2"></i>${data.icon}
                            </span>`
                        );
                        $('#view-route').html(data.route ?
                            `<code class="px-2 py-1 bg-light rounded border">${data.route}</code>` :
                            '<span class="text-muted fst-italic">No route specified</span>'
                        );
                        $('#view-permission').html(data.permission ?
                            `<span class="badge bg-primary-subtle text-primary border px-3 py-2">${data.permission}</span>` :
                            '<span class="text-muted fst-italic">No permission required</span>'
                        );

                        // Parent menu display
                        $('#view-parent').html(data.parent ?
                            `<div class="d-flex align-items-center">
                                <span class="badge bg-info-subtle text-info border me-2">
                                    <i class="${data.parent.icon}"></i>
                                </span>
                                <span class="fw-medium">${data.parent.name}</span>
                            </div>` :
                            '<span class="text-muted fst-italic">No parent menu</span>'
                        );

                        // Children menus display
                        const childrenContainer = $('#view-children');
                        childrenContainer.empty();

                        if (data.children && data.children.length > 0) {
                            data.children.forEach(child => {
                                childrenContainer.append(`
                                    <div class="list-group-item border-0 border-bottom">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="badge bg-info-subtle text-info border me-2">
                                                <i class="${child.icon}"></i>
                                            </span>
                                            <span class="fw-medium">${child.name}</span>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            childrenContainer.append(`
                                <div class="list-group-item border-0">
                                    <div class="text-muted fst-italic">
                                        <i class="ti ti-folder-off me-2"></i>No child menus
                                    </div>
                                </div>
                            `);
                        }
                    }
                });
            });
        });
    </script>
@endpush
