<script src="{{ asset('dist/js/scripts.js') }}"></script>
<script src="{{ asset('dist/js/custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.4/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    window.bcAdminEditor = {
        uploadUrl: @json(auth('admin')->check() ? route('admin_editor_image') : ''),
        csrf: @json(csrf_token())
    };
</script>
<script src="{{ asset('js/admin-editor.js') }}?v=3"></script>