<li data-id="{{ $task->id }}">
    <div class="card border-left-{{ $task->completed ? 'success' : 'danger' }} 
        @if($loop->iteration % 2 == 1) bg-body-secondary @endif">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-3">
                    {{ $task->title }}
                </div>
                <div class="col-7 col-desc">
                    <p class="p-desc m-0 p-0">
                        {{ $task->description }}
                    </p>
                    <textarea name="description" data-id="{{ $task->id }}" rows="1" style="display: none" class="form-control area-desc">{{ $task->description }}</textarea>
                </div>
                <div class="col-1 text-center">
                    <input type="checkbox" class="is-completed" data-id="{{ $task->id }}" {{ $task->completed ? 'checked' : '' }}>
                </div>
                <div class="col-1 text-center">
                    <button class="btn btn-danger btn-sm delete" data-id="{{ $task->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</li>