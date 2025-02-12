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
                <div class="row g-4">
                    <!-- User Information -->
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="ti ti-user-circle me-2 fs-5"></i>
                                <h5 class="card-title mb-0">User Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Name</label>
                                    <p id="view-name" class="form-control-plaintext border-bottom"></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Email</label>
                                    <p id="view-email" class="form-control-plaintext border-bottom"></p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Created At</label>
                                    <p id="view-created" class="form-control-plaintext border-bottom"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roles & Permissions -->
                    <div class="col-md-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="ti ti-shield-lock me-2 fs-5"></i>
                                <h5 class="card-title mb-0">Access & Permissions</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Assigned Roles</label>
                                    <div id="view-roles" class="d-flex flex-wrap gap-2"></div>
                                </div>
                                <div>
                                    <label class="form-label fw-bold text-muted">Direct Permissions</label>
                                    <div id="view-permissions" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.view-item', function() {
                $('#{{ $page_settings['action']['view']['id_modal'] }}').modal('show');
                const id = $(this).data('id');

                $.ajax({
                    url: "{{ $page_settings['action']['view']['route_show'] }}".replace(':id', id),
                    type: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        const user = response.data;

                        // Set user details
                        $('#detail_title').text(`User Details: ${user.name}`);
                        $('#view-name').text(user.name);
                        $('#view-email').text(user.email);
                        $('#view-created').text(user.created_at);



                        // Display roles
                        const rolesContainer = $('#view-roles');
                        rolesContainer.empty();

                        if (user.roles && user.roles.length > 0) {
                            user.roles.forEach(role => {
                                rolesContainer.append(`
                            <span class="badge bg-primary">
                                <i class="ti ti-shield me-1"></i>${role.name}
                            </span>
                        `);
                            });
                        } else {
                            rolesContainer.html(
                                '<span class="text-muted">No roles assigned</span>');
                        }

                        // Display permissions
                        const permissionsContainer = $('#view-permissions');
                        permissionsContainer.empty();

                        if (user.permissions && user.permissions.length > 0) {
                            user.permissions.forEach(permission => {
                                permissionsContainer.append(`
                            <span class="badge bg-info">
                                <i class="ti ti-key me-1"></i>${permission.name}
                            </span>
                        `);
                            });
                        } else {
                            permissionsContainer.html(
                                '<span class="text-muted">No direct permissions</span>');
                        }
                    },
                    error: function(xhr) {
                        Toastify({
                            text: "Failed to load user details",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            style: {
                                background: "rgb(var(--danger),1)",
                            },
                        }).showToast();
                    }
                });
            });
        });
    </script>
@endpush
