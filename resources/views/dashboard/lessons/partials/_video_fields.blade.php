<div id="field-video" class="mb-3 type-field" style="display: none;">
    <label for="video_url" class="form-label fw-semibold text-dark">
        Video URL <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light text-muted">
            <i class="fa-solid fa-link"></i>
        </span>
        <input 
            type="url" 
            name="video_url" 
            id="video_url" 
            class="form-control @error('video_url') is-invalid @enderror" 
            value="{{ old('video_url', $lesson->video_url ?? '') }}" 
            placeholder="https://www.youtube.com/watch?v=... or Vimeo link"
        >
        @error('video_url')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-text text-muted">
        Supported links: YouTube, Vimeo, MP4 direct video URLs, or embed links.
    </div>
</div>