@extends('layouts.admin')

@section('title', isset($category) ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<div class="columns is-centered">
    <div class="column is-6">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    {{ isset($category) ? 'Sửa danh mục: ' . $category->name : 'Thêm danh mục mới' }}
                </p>
            </div>
            <div class="card-content">
                <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
                    @csrf
                    @if(isset($category)) @method('PUT') @endif
                    
                    <div class="field">
                        <label class="label">Tên danh mục *</label>
                        <div class="control">
                            <input class="input @error('name') is-danger @enderror" type="text" name="name" 
                                   value="{{ old('name', $category->name ?? '') }}" required>
                        </div>
                        @error('name')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="field">
                        <label class="label">Slug (tự động tạo nếu để trống)</label>
                        <div class="control">
                            <input class="input" type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Icon (Font Awesome class, vd: fa-facebook)</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="fa-folder">
                            <span class="icon is-left">
                                <i class="fas {{ old('icon', $category->icon ?? 'fa-folder') }}"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Mô tả</label>
                        <div class="control">
                            <textarea class="textarea" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Thứ tự sắp xếp</label>
                        <div class="control">
                            <input class="input" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="checkbox">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                            Kích hoạt
                        </label>
                    </div>
                    
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                <i class="fas fa-save mr-2"></i> Lưu
                            </button>
                        </div>
                        <div class="control">
                            <a href="{{ route('admin.categories.index') }}" class="button is-light">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
