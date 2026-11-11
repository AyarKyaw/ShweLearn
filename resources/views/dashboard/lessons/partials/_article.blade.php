<div class="col-md-12 type-field field-article" style="display: {{ old('type', $lesson->type ?? 'article') == 'article' ? 'block' : 'none' }};">
    <label for="content_article" class="form-label fw-bold">Article Body Content <span class="text-danger">*</span></label>
    <textarea name="content" id="content_article" rows="8" class="form-control @error('content') is-invalid @enderror" placeholder="Write full article content here...">{{ old('content', $lesson->content) }}</textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>