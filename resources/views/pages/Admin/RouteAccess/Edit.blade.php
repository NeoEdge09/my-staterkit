<div class="modal fade" id="{{ $page_settings['action']['edit']['id_modal'] }}" aria-labelledby="editModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning-800">
                <h1 class="modal-title fs-5 text-white" id="editModal">
                    <i class="ti ti-pencil me-2"></i>Edit Route Access
                </h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['edit']['id_form'] }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <!-- Route Information Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-route me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Route Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="edit_route_name" class="form-label">Route Name</label>
                                            <select class="select2 form-select" id="edit_route_name" name="route_name">
                                                <option value="">-- Select Route --</option>
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route['value'] }}">{{ $route['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Access Control Card -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-shield-lock me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Access Control</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_role" class="form-label">Role</label>
                                            <select class="select2 form-select" id="edit_role" name="role">
                                                <option value="">-- Select Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_permission" class="form-label">Permission</label>
                                            <select class="select2 form-select" id="edit_permission" name="permission">
                                                <option value="">-- Select Permission --</option>
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->id }}">{{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="update">
                    <i class="ti ti-device-floppy me-1"></i>Update Route Access
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for edit modal
            $('.select2', '#{{ $page_settings['action']['edit']['id_modal'] }}').select2({
                dropdownParent: $('#{{ $page_settings['action']['edit']['id_modal'] }}'),
                width: '100%'
            });

            // Handle edit button click
            $('body').on('click', '.edit-item', function() {
                let id = $(this).data('id');
                $('#edit_id').val(id);

                // Fetch data for editing
                $.get("{{ $page_settings['action']['edit']['getData_route'] }}".replace(':id', id),
                    function(response) {

                        $('#edit_route_name').val(response.data.route_name).trigger('change');
                        $('#edit_role').val(response.data.role_id).trigger('change');
                        $('#edit_permission').val(response.data.permission_id).trigger('change');
                        $('#{{ $page_settings['action']['edit']['id_modal'] }}').modal('show');
                    });
            });

            // Handle update button click
            $('#update').click(function() {
                $(this).prop('disabled', true);
                $(this).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Updating...');

                let id = $('#edit_id').val();
                let formData = {
                    route_name: $('#edit_route_name').val(),
                    role: $('#edit_role').val(),
                    permission: $('#edit_permission').val(),
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT'
                };

                $.ajax({
                    url: "{{ $page_settings['action']['edit']['update_route'] }}".replace(':id',
                        id),
                    type: "POST",
                    data: formData,
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
                                background: "rgb(var(--warning),1)",
                            },
                        }).showToast();
                        resetUpdateButton();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]',
                                    '#{{ $page_settings['action']['edit']['id_form'] }}'
                                );
                                input.addClass('is-invalid');
                                input.after('<div class="invalid-feedback">' + value[
                                    0] + '</div>');
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
                            alert("An error occurred, please try again.");
                        }
                        resetUpdateButton();
                    }
                });
            });

            // Reset invalid state on input change
            $(document).on('input change', '.is-invalid', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });

        function resetUpdateButton() {
            $("#update").prop('disabled', false);
            $("#update").html('<i class="ti ti-device-floppy me-1"></i>Update Route Access');
        }
    </script>
@endpush
