@extends('layouts.admin')

@section('title', 'Thêm bài viết mới')

@section('content')
<div class="level">
    <div class="level-left">
        <h1 class="title">Thêm bài viết mới</h1>
    </div>
    <div class="level-right">
        <a href="{{ route('admin.posts.index') }}" class="button">
            <span class="icon"><i class="fas fa-arrow-left"></i></span>
            <span>Quay lại</span>
        </a>
    </div>
</div>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="columns">
        <div class="column is-8">
            <div class="card">
                <div class="card-content">
                    <div class="field">
                        <label class="label">Tiêu đề <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input class="input @error('title') is-danger @enderror" type="text" name="title" value="{{ old('title') }}" required>
                        </div>
                        @error('title')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label">Slug (URL)</label>
                        <div class="control">
                            <input class="input @error('slug') is-danger @enderror" type="text" name="slug" value="{{ old('slug') }}" placeholder="Để trống sẽ tự động tạo từ tiêu đề">
                        </div>
                        @error('slug')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="label">Tóm tắt</label>
                        <div class="control">
                            <textarea class="textarea" name="excerpt" rows="3" placeholder="Tóm tắt ngắn về bài viết...">{{ old('excerpt') }}</textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Nội dung <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <textarea class="textarea @error('content') is-danger @enderror" name="content" rows="15" required>{{ old('content') }}</textarea>
                        </div>
                        <p class="help">Hỗ trợ HTML</p>
                        @error('content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-4">
            <div class="card">
                <div class="card-content">
                    <h3 class="title is-5 mb-4">Xuất bản</h3>
                    
                    <div class="field">
                        <label class="label">Trạng thái</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="status">
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Đăng ngay</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Ảnh đại diện</label>
                        <div class="control">
                            <input class="input" type="file" name="thumbnail" accept="image/*">
                        </div>
                        <p class="help">Tối đa 2MB, hỗ trợ JPG, PNG, GIF</p>
                    </div>

                    <button type="submit" class="button is-primary is-fullwidth mt-4">
                        <span class="icon"><i class="fas fa-save"></i></span>
                        <span>Lưu bài viết</span>
                    </button>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-content">
                    <h3 class="title is-5 mb-4">SEO</h3>

                    <div class="field">
                        <label class="label">Meta Title</label>
                        <div class="control">
                            <input class="input" type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Tiêu đề SEO">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Meta Description</label>
                        <div class="control">
                            <textarea class="textarea" name="meta_description" rows="3" placeholder="Mô tả SEO...">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Meta Keywords</label>
                        <div class="control">
                            <input class="input" type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="từ khóa 1, từ khóa 2, ...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
