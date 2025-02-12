<div class="modal fade" id="{{ $page_settings['action']['edit']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['edit']['id_modal'] }}" aria-labelledby="editModalRoles" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h1 class="modal-title fs-5 text-white" id="editModal">Edit Role</h1>
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
                                    <h5 class="card-title mb-0">Role</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label for="name" class="form-label">Role Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="guard_name" class="form-label">Guard</label>
                                            <select class="form-select" name="guard_name" required>
                                                <option value="web">web</option>
                                                <option value="api">api</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Permissions</h5>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="checkPermissionAllEdit"
                                            value="1">
                                        <label class="form-check-label" for="checkPermissionAllEdit">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @php $i = 1; @endphp
                                    @foreach ($permission_groups as $group)
                                        <div class="permission-group mb-4">
                                            @php
                                                $permissions = App\Traits\User::getpermissionsByGroupName($group->name);
                                                $j = 1;
                                            @endphp
                                            <div class="permission-group-header mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="{{ $i }}Management_edit"
                                                        value="{{ $group->name }}"
                                                        onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                                    <label class="form-check-label fw-bold"
                                                        for="{{ $i }}Management_edit">
                                                        {{ ucfirst($group->name) }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div
                                                class="permission-group-body role-{{ $i }}-management-checkbox">
                                                <div class="row g-3">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    name="permissions[]"
                                                                    id="checkPermissionEdit{{ $permission->id }}"
                                                                    onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management_edit', {{ count($permissions) }})"
                                                                    value="{{ $permission->name }}">
                                                                <label class="form-check-label"
                                                                    for="checkPermissionEdit{{ $permission->id }}">
                                                                    {{ str_replace($group->name . ' ', '', $permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-secondary" onclick="reset()">Reset</button>
                <button type="submit" class="btn btn-primary" id="update">
                    <i class="fa-solid fa-save me-1"></i> Update Role
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Form submission handler
            $(document).on('click', '.edit-item', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $('#{{ $page_settings['action']['edit']['id_form'] }}').data('role-id', id);
                loadRoleData(id);
            });

            function loadRoleData(id) {
                $.ajax({
                    url: "{{ $page_settings['action']['edit']['getData_route'] }}".replace(':id', id),
                    type: "GET",
                    success: function(response) {
                        const role = response.role;
                        $('#{{ $page_settings['action']['edit']['id_modal'] }}').modal('show');

                        // Fill basic information
                        $('input[name="name"]').val(role.name);
                        $('select[name="guard_name"]').val(role.guard_name);

                        // Reset all checkboxes
                        $('input[type="checkbox"]').prop('checked', false);

                        // Check permissions
                        if (role.permissions && Array.isArray(role.permissions)) {
                            role.permissions.forEach(permission => {
                                $(`input[name="permissions[]"][value="${permission}"]`).prop(
                                    'checked', true);
                            });

                            // Update group checkboxes
                            $('.permission-group').each(function() {
                                const groupClass = $(this).find('.permission-group-body').attr(
                                    'class').split(' ')[1];
                                const groupId = groupClass.split('-')[1] + 'Management_edit';
                                const totalPermissions = $('.' + groupClass +
                                    ' input[type="checkbox"]').length;
                                const checkedPermissions = $('.' + groupClass +
                                    ' input[type="checkbox"]:checked').length;

                                $('#' + groupId).prop('checked', totalPermissions ===
                                    checkedPermissions);
                            });

                            // Update "Select All" checkbox
                            implementAllChecked();
                        }
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            }
            // Form submission handler
            $('#update').click(function(e) {
                e.preventDefault();
                const roleId = $('#{{ $page_settings['action']['edit']['id_form'] }}').data('role-id');
                updateForm(roleId);
            });

            function updateForm(roleId) {
                $("#update").prop('disabled', true);
                $("#update").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Updating...');

                let formData = $('#{{ $page_settings['action']['edit']['id_form'] }}').serialize();
                $.ajax({
                    url: "{{ $page_settings['action']['edit']['update_route'] }}".replace(':id', roleId),
                    type: "POST",
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
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    },
                    complete: function() {
                        resetSubmitButton();
                    }
                });
            }

            function updateGroupCheckboxes() {
                $('.permission-group').each(function() {
                    const groupClass = $(this).find('.permission-group-body').attr('class').split(' ')[1];
                    const groupId = groupClass.split('-')[1] + 'Management_edit';
                    const totalPermissions = $('.' + groupClass + ' input[type="checkbox"]').length;
                    const checkedPermissions = $('.' + groupClass + ' input[type="checkbox"]:checked')
                        .length;

                    $('#' + groupId).prop('checked', totalPermissions === checkedPermissions);
                });
            }

            // Permission handling
            $("#checkPermissionAllEdit").click(function() {
                $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
            });

            function checkPermissionByGroup(className, checkThis) {
                const groupIdName = $("#" + checkThis.id);
                const classCheckBox = $('.' + className + ' input');
                classCheckBox.prop('checked', groupIdName.is(':checked'));
                implementAllChecked();
            }

            function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
                const classCheckbox = $('.' + groupClassName + ' input');
                const groupIDCheckBox = $("#" + groupID);

                groupIDCheckBox.prop('checked',
                    $('.' + groupClassName + ' input:checked').length === countTotalPermission
                );
                implementAllChecked();
            }

            function implementAllChecked() {
                const totalCheckboxes = $('input[type="checkbox"]').length;
                const checkedBoxes = $('input[type="checkbox"]:checked').length;
                $("#checkPermissionAllEdit").prop('checked', totalCheckboxes === checkedBoxes);
            }

            // Helper functions
            function resetSubmitButton() {
                $("#update").prop('disabled', false);
                $("#update").html('<i class="fa-solid fa-save me-1"></i> Save Role');
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

            // Permission handling
            $("#checkPermissionAllEdit").click(function() {
                const isChecked = $(this).is(':checked');
                $('input[type=checkbox]').prop('checked', isChecked);
            });

            // Modified group permission handler
            $('[id$="Management_edit"]').click(function() {
                const groupId = $(this).attr('id');
                const isChecked = $(this).is(':checked');
                const groupClass = 'role-' + groupId.replace('Management_edit', '') +
                    '-management-checkbox';

                // Check/uncheck all permissions in the group
                $('.' + groupClass + ' input[type="checkbox"]').prop('checked', isChecked);

                // Update "Select All" checkbox state
                implementAllChecked();
            });

            // Individual permission handler
            $('.permission-checkbox').click(function() {
                const groupClass = $(this).closest('.permission-group-body').attr('class').split(' ')[1];
                const groupId = groupClass.split('-')[1] + 'Management_edit';
                const totalPermissions = $('.' + groupClass + ' input[type="checkbox"]').length;
                const checkedPermissions = $('.' + groupClass + ' input[type="checkbox"]:checked').length;

                // Update group checkbox state
                $('#' + groupId).prop('checked', totalPermissions === checkedPermissions);

                // Update "Select All" checkbox state
                implementAllChecked();
            });

            function implementAllChecked() {
                const totalCheckboxes = $('.permission-checkbox').length;
                const checkedBoxes = $('.permission-checkbox:checked').length;
                $("#checkPermissionAllEdit").prop('checked', totalCheckboxes === checkedBoxes);
            }


            // Remove validation errors on input
            $(document).on('input change', '.is-invalid', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });

            $('#{{ $page_settings['action']['edit']['id_modal'] }}').on('hidden.bs.modal', function() {
                $('#{{ $page_settings['action']['edit']['id_form'] }}')[0].reset();
                $('input[type="checkbox"]').prop('checked', false);
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });
        });
    </script>
@endpush
