<div class="col-md-12 type-field field-homework" style="display: {{ old('type', $lesson->type) == 'homework' ? 'block' : 'none' }};">
    <div class="alert alert-info py-2 mb-3">
        <i class="fa-solid fa-circle-info me-1"></i>
        Students will be prompted to submit their file when viewing this lesson.
    </div>

    <div class="mb-3">
        <label for="homework_file" class="form-label fw-bold">Homework Template / Reference File (Optional)</label>
        @if(!empty($lesson->homework_file_path))
            <div class="mb-2">
                <span class="badge bg-secondary me-2"><i class="fa-solid fa-file-arrow-down me-1"></i> Current File</span>
                <a href="{{ Storage::url($lesson->homework_file_path) }}" target="_blank" class="small text-decoration-underline">View Reference File</a>
            </div>
        @endif
        <input type="file" name="homework_file" id="homework_file" class="form-control @error('homework_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
        <small class="text-muted">Allowed formats: PDF, Word, Excel, PowerPoint. Leave blank to keep existing file.</small>
        @error('homework_file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="content_homework" class="form-label fw-bold">Homework Task Instructions <span class="text-danger">*</span></label>
        <textarea name="content" id="content_homework" rows="4" class="form-control @error('content') is-invalid @enderror" placeholder="Describe the assignment instructions and criteria for completion...">{{ old('content', $lesson->content) }}</textarea>
        @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>