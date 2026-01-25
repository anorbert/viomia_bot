<form id="editBotForm" method="POST" action="{{ route('admin.bots.update', $bot->id) }}">
    @csrf
    @method('PUT')

    <div class="p-2">
        <div id="edit-error" class="alert alert-danger" style="display:none;"></div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $bot->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Version</label>
            <input type="text" name="version" value="{{ $bot->version }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Address / Downloads</label>
            <input type="text" name="address" value="{{ $bot->address }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Active" {{ $bot->status === 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $bot->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:0;">
            <label>Description</label>
            <textarea name="description" rows="3" class="form-control">{{ $bot->description }}</textarea>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">
            <i class="fa fa-save"></i> Save Changes
        </button>
    </div>
</form>
