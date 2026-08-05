@include('admin.partials._editorial_fields', [
    'model' => $post ?? null,
    'editorialOptions' => $editorialOptions ?? null,
])
