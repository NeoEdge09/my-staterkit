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
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_name" class="form-label">Permission Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_module" class="form-label">Module Name</label>
                                <input type="text" class="form-control" id="edit_module" name="module" required>
                                <small class="form-text text-muted">Example: users, roles, settings</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_guard_name" class="form-label">Guard Name</label>
                                <select class="form-select" id="edit_guard_name" name="guard_name" required>
                                    <option value="web">web</option>
                                    <option value="api">api</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <input type="hidden" name="id" id="id">

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light-light" data-bs-dismiss="modal">Close</button>
                <div>
                    <button type="button" class="btn btn-light-secondary" onclick="reset()">Reset</button>
                    <button type="submit" class="btn btn-light-warning" id="save">Save Changes</button>
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
                    success: function(response) {
                        const permission = response.permission;
                        $('#id').val(permission.id);
                        $('#edit_name').val(permission.name);
                        $('#edit_module').val(permission.module);
                        $('#edit_guard_name').val(permission.guard_name);
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            $('#save').on('click', function() {
                $('#save').prop('disabled', true);
                $("#save").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');

                let id = $('#id').val();
                let updateUrl = "{{ $page_settings['action']['edit']['update_route'] }}".replace(':id', id);
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
                        resetSubmitButton();
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
                        }
                        resetSubmitButton();
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
