@extends('admin.layout.app')

@section('heading', 'Edit Guide Topic')

@section('button')
    <a href="{{ route('admin_broker_guide_topics_index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> All topics</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin_broker_guide_topics_update', $topic->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.broker_guide_topics._form', ['topic' => $topic, 'contextProfiles' => $contextProfiles])
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Save topic</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
