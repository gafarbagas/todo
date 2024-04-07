@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    <div class="row mt-4">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <i class="fa-solid fa-pencil"></i> My Tasks
                </div>
                <div>
                    <button class="btn btn-primary btn-sm button-custom" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"><i class="fas fa-plus"></i> Add New</button>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">Title</div>
                        <div class="col-7">Description</div>
                        <div class="col-1 text-center">Completed</div>
                        <div class="col-1 text-center">Action</div>
                    </div>
                </div>
            </div>
            <ul id="sortable">
                @forelse ($tasks as $task)
                    @include('dashboard._task', ['task' => $task])
                @empty
                    <h5 id="no-task" class="text-center">You have no task</h5>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks') }}" method="post">
                        @csrf
                        <div class="col-md-12 mb-2">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                            <small class="text-danger text-sm"></small>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>
                            <small class="text-danger text-sm"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="add-button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/task.js') }}"></script>
@endsection
