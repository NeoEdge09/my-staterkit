<div class="modal fade" id="{{ $page_settings['action']['edit']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['edit']['id_modal'] }}" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning-800">
                <h1 class="modal-title fs-5 text-white">
                    <i class="ti ti-edit me-2"></i>Edit {{ $page_settings['title'] }}
                </h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['edit']['id_form'] }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Basic Information Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-file-description me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_name" class="form-label">Menu Name</label>
                                            <input type="text" class="form-control" id="edit_name" name="name"
                                                placeholder="Enter menu name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_icon" class="form-label">Icon Code</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-code"></i>
                                                </span>
                                                <input type="text" class="form-control" id="edit_icon" name="icon"
                                                    placeholder="e.g., ti ti-home">
                                            </div>
                                            <small class="text-muted">Use Tabler Icons code</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Settings Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-route me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Navigation Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_route" class="form-label">Route</label>
                                            <select class="select2 form-select" id="edit_route" name="route">
                                                <option value="">-- Select Route --</option>
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route['value'] }}">{{ $route['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_role" class="form-label">Role</label>
                                            <select class="select2 form-select" id="edit_role" name="role">
                                                <option value="">-- Select role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_permission" class="form-label">Permission</label>
                                            <select class="select2 form-select" id="edit_permission" name="permission">
                                                <option value="">-- Select permission --</option>
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->name }}">{{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_parent_id" class="form-label">Parent Menu</label>
                                            <select class="select2 form-select" id="edit_parent_id" name="parent_id">
                                                <option value="">-- Select Parent --</option>
                                                @foreach ($menus as $menu)
                                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_order" class="form-label">Display Order</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-sort-ascending"></i>
                                                </span>
                                                <input type="number" class="form-control" id="edit_order"
                                                    name="order" placeholder="Menu order" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <div>
                    <button type="button" class="btn btn-secondary" onclick="reset()">
                        <i class="ti ti-refresh me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-warning" id="save">
                        <i class="ti ti-device-floppy me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.edit-item', function() {
                $('#{{ $page_settings['action']['edit']['id_modal'] }}').modal('show')
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ $page_settings['action']['edit']['getData_route'] }}".replace(':id',
                        id),
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        const item = data.data
                        console.log(item)
                        $('#id').val(item.id);
                        $('#edit_name').val(item.name);
                        $('#edit_icon').val(item.icon);
                        $('#edit_route').val(item.route).trigger('change');
                        $('#edit_role').val(item.role).trigger('change');
                        $('#edit_permission').val(item.permission).trigger('change');
                        $('#edit_order').val(item.order);
                        $('#edit_parent_id').val(item.parent_id).trigger('change');
                    }

                });

            });

            $('#save').on('click', function() {
                $('#save').prop('disabled', true);
                $('#save').html("Saving...");
                $("#save").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');


                let id = $('#id').val();
                let updateUrl = "{{ $page_settings['action']['edit']['update_route'] }}".replace(':id',
                    id);
                let formData = $('#{{ $page_settings['action']['edit']['id_form'] }}').serialize();
                $.ajax({
                    url: updateUrl,
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
                        resetSubmitButton()

                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
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
                        } {

                        } else {
                            alert("Terjadi kesalahan, silakan coba lagi.");
                        }


                        resetSubmitButton()
                    }

                });
            });
        });

        function reset() {
            $('#{{ $page_settings['action']['edit']['id_form'] }}')[0].reset();
            $('.select2').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function resetSubmitButton() {
            $("#save").prop('disabled', false);
            $("#save").html("Save Changes");
        }
    </script>
@endpush
