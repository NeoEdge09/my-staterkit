<div class="modal fade" id="{{ $page_settings['action']['edit']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['edit']['id_modal'] }}" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning-800">
                <h1 class="modal-title fs-5 text-white">Edit {{ $page_settings['title'] }}</h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['edit']['id_form'] }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">User Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="edit_name" name="name"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="edit_email" name="email"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="edit_password"
                                                name="password">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_password_confirmation" class="form-label">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control" id="edit_password_confirmation"
                                                name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Roles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach ($roles as $role)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input role-checkbox"
                                                        name="roles[]" id="edit_role_{{ $role->id }}"
                                                        value="{{ $role->name }}">
                                                    <label class="form-check-label" for="edit_role_{{ $role->id }}">
                                                        {{ ucfirst($role->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Permissions -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Direct Permissions</h5>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="edit_checkPermissionAll">
                                        <label class="form-check-label" for="edit_checkPermissionAll">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach ($permission_groups as $group)
                                        <div class="permission-group mb-4">
                                            @php
                                                $permissions = App\Traits\User::getpermissionsByGroupName($group->name);
                                            @endphp
                                            <div class="permission-group-header mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input group-checkbox"
                                                        id="edit_{{ $group->name }}_group">
                                                    <label class="form-check-label fw-bold">
                                                        {{ ucfirst($group->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="permission-group-body">
                                                <div class="row g-3">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    name="permissions[]"
                                                                    id="edit_permission_{{ $permission->id }}"
                                                                    value="{{ $permission->name }}"
                                                                    data-group="{{ $group->name }}">
                                                                <label class="form-check-label"
                                                                    for="edit_permission_{{ $permission->id }}">
                                                                    {{ str_replace($group->name . ' ', '', $permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <div>
                    <button type="button" class="btn btn-secondary" onclick="reset()">Reset</button>
                    <button type="submit" class="btn btn-warning" id="save">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Edit modal show handler
            $('body').on('click', '.edit-item', function() {
                reset();
                $('#{{ $page_settings['action']['edit']['id_modal'] }}').modal('show');
                const id = $(this).data('id');

                $.ajax({
                    url: "{{ $page_settings['action']['edit']['getData_route'] }}".replace(':id',
                        id),
                    type: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        const user = response.data;
                        $('#id').val(user.id);
                        $('#edit_name').val(user.name);
                        $('#edit_email').val(user.email);



                        // Set roles
                        if (user.roles) {
                            user.roles.forEach(role => {
                                $(`#edit_role_${role.id}`).prop('checked', true);
                            });
                        }

                        // Set permissions
                        if (user.permissions) {
                            user.permissions.forEach(permission => {
                                $(`#edit_permission_${permission.id}`).prop('checked',
                                    true);
                            });
                            updateGroupCheckboxes();
                            updateSelectAllState();
                        }
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            // Save changes handler
            $('#save').on('click', function() {
                $(this).prop('disabled', true);
                $(this).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');

                const id = $('#id').val();
                const formData = $('#{{ $page_settings['action']['edit']['id_form'] }}').serialize();

                $.ajax({
                    url: "{{ $page_settings['action']['edit']['update_route'] }}".replace(':id',
                        id),
                    type: "PUT",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#{{ $page_settings['action']['edit']['id_modal'] }}').modal('hide');
                        $('#{{ $table_settings['id'] }}').DataTable().ajax.reload();

                        Toastify({
                            text: response.message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            style: {
                                background: "rgb(var(--success),1)",
                            },
                        }).showToast();

                        resetSubmitButton();
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                        resetSubmitButton();
                    }
                });
            });

            // Permission handling
            $('#edit_checkPermissionAll').click(function() {
                const isChecked = $(this).is(':checked');
                $('.permission-checkbox, .group-checkbox').prop('checked', isChecked);
            });

            $('.group-checkbox').click(function() {
                const groupName = $(this).attr('id').replace('edit_', '').replace('_group', '');
                const isChecked = $(this).is(':checked');
                $(`input[data-group="${groupName}"]`).prop('checked', isChecked);
                updateSelectAllState();
            });

            $('.permission-checkbox').click(function() {
                updateGroupCheckboxes();
                updateSelectAllState();
            });
        });

        function updateGroupCheckboxes() {
            $('.group-checkbox').each(function() {
                const groupName = $(this).attr('id').replace('edit_', '').replace('_group', '');
                const totalPermissions = $(`input[data-group="${groupName}"]`).length;
                const checkedPermissions = $(`input[data-group="${groupName}"]:checked`).length;
                $(this).prop('checked', totalPermissions === checkedPermissions && totalPermissions > 0);
            });
        }

        function updateSelectAllState() {
            const totalPermissions = $('.permission-checkbox').length;
            const checkedPermissions = $('.permission-checkbox:checked').length;
            $('#edit_checkPermissionAll').prop('checked', totalPermissions === checkedPermissions && totalPermissions > 0);
        }

        function reset() {
            $('#{{ $page_settings['action']['edit']['id_form'] }}')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function resetSubmitButton() {
            $('#save').prop('disabled', false);
            $('#save').html('Save Changes');
        }

        function handleAjaxError(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(xhr.responseJSON.errors, function(key, value) {
                    let input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            } else if (xhr.status === 403) {
                Toastify({
                    text: "You don't have permission to perform this action",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    style: {
                        background: "rgb(var(--danger),1)",
                    },
                }).showToast();
            } else {
                Toastify({
                    text: "An error occurred. Please try again.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    style: {
                        background: "rgb(var(--danger),1)",
                    },
                }).showToast();
            }
        }
    </script>
@endpush
