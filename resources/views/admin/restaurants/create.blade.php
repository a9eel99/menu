@extends('layouts.admin')

@section('title', __('app.add_restaurant'))
@section('page-title', __('app.add_restaurant'))

@push('styles')
<style>
    .menu-type-selection {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .menu-type-option {
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s;
        background: #fff;
    }
    .menu-type-option:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
    }
    .menu-type-option.active {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.05);
    }
    .menu-type-option input { display: none; }
    .menu-type-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .menu-type-icon.digital {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }
    .menu-type-icon.pdf {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
    }
    .menu-type-info h6 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
        color: #1f2937;
    }
    .menu-type-info p {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
    }

    .pdf-upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }
    .pdf-upload-zone:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
    }
    .pdf-upload-zone.drag-over {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.05);
    }
    .pdf-upload-zone .upload-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .pdf-upload-zone .upload-icon i {
        font-size: 1.5rem;
        color: #64748b;
    }
    .pdf-upload-zone h5 {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 6px;
    }
    .pdf-upload-zone p {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 16px;
    }
    .pdf-upload-zone .file-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        font-size: 0.8rem;
        color: #94a3b8;
    }
    .pdf-upload-zone .file-info span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .pdf-upload-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .selected-file {
        display: none;
        margin-top: 16px;
        padding: 12px 16px;
        background: #ecfdf5;
        border-radius: 10px;
        align-items: center;
        gap: 10px;
    }
    .selected-file.show {
        display: flex;
    }
    .selected-file i {
        color: #10b981;
    }
    .selected-file span {
        flex: 1;
        font-size: 0.85rem;
        color: #059669;
        font-weight: 500;
    }

    /* Image Upload Zone */
    .image-upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
        min-height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .image-upload-zone:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
    }
    .image-upload-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .image-upload-zone .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        color: #64748b;
    }
    .image-upload-zone .upload-placeholder i {
        font-size: 2.5rem;
        color: #cbd5e1;
    }
    .image-upload-zone .upload-placeholder span {
        font-weight: 600;
        color: #475569;
    }
    .image-upload-zone .upload-placeholder small {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .image-upload-zone .image-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 3;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
    .image-upload-zone .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }
    .image-upload-zone.cover {
        min-height: 200px;
    }
    .image-upload-zone.cover .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }
    .image-upload-zone .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        z-index: 10;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
    .image-upload-zone .remove-image-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="create-page wide">
    <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        {{-- نوع المطعم --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-info-circle"></i>
                {{ __('app.restaurant_type') }}
            </div>
            <div class="form-card-body">
                <div class="type-selection">
                    <label class="type-option">
                        <input type="radio" name="restaurant_type" value="main" checked onchange="toggleParentSelect()">
                        <div class="type-option-content">
                            <div class="type-icon main">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ __('app.main_restaurant') }}</h6>
                                <p>{{ __('app.main_restaurant_desc') }}</p>
                            </div>
                        </div>
                    </label>
                    
                    <label class="type-option">
                        <input type="radio" name="restaurant_type" value="branch" onchange="toggleParentSelect()">
                        <div class="type-option-content">
                            <div class="type-icon branch">
                                <i class="fas fa-code-branch"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ __('app.branch') }}</h6>
                                <p>{{ __('app.branch_desc') }}</p>
                            </div>
                        </div>
                    </label>
                </div>
                
                <div class="parent-select-wrapper" id="parent-select">
                    <label class="form-label">{{ __('app.parent_restaurant') }} <span class="required">*</span></label>
                    <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">-- {{ __('app.select') }} --</option>
                        @foreach($mainRestaurants as $main)
                        <option value="{{ $main->id }}" {{ request('parent_id') == $main->id ? 'selected' : '' }}>
                            {{ app()->getLocale() == 'ar' ? $main->name_ar : $main->name_en }}
                        </option>
                        @endforeach
                    </select>
                    @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- المعلومات الأساسية --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-edit"></i>
                {{ __('app.basic_info') }}
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_name_ar') }} <span class="required">*</span></label>
                        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar') }}" placeholder="{{ __('app.enter_name_ar') }}" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_name_en') }} <span class="required">*</span></label>
                        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}" placeholder="{{ __('app.enter_name_en') }}" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_description_ar') }}</label>
                        <textarea name="description_ar" class="form-control" rows="3" placeholder="{{ __('app.description_placeholder') }}">{{ old('description_ar') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_description_en') }}</label>
                        <textarea name="description_en" class="form-control" rows="3" placeholder="{{ __('app.description_placeholder_en') }}">{{ old('description_en') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- الموقع وساعات العمل --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-map-marker-alt"></i>
                {{ __('app.location_and_hours') }}
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.address_ar') }}</label>
                        <textarea name="address_ar" class="form-control" rows="2" placeholder="{{ __('app.address_placeholder') }}">{{ old('address_ar') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.address_en') }}</label>
                        <textarea name="address_en" class="form-control" rows="2" placeholder="{{ __('app.address_placeholder_en') }}">{{ old('address_en') }}</textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.working_hours_ar') }}</label>
                        <input type="text" name="working_hours_ar" class="form-control" value="{{ old('working_hours_ar') }}" placeholder="{{ __('app.working_hours_placeholder') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.working_hours_en') }}</label>
                        <input type="text" name="working_hours_en" class="form-control" value="{{ old('working_hours_en') }}" placeholder="e.g. 9 AM - 11 PM">
                    </div>
                </div>
            </div>
        </div>

        {{-- معلومات الاتصال --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-phone-alt"></i>
                {{ __('app.contact_info') }}
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+966 5x xxx xxxx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.google_maps') }}</label>
                        <input type="url" name="google_maps_url" class="form-control" value="{{ old('google_maps_url') }}" placeholder="https://maps.google.com/...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.google_reviews') }}</label>
                        <input type="url" name="google_reviews_url" class="form-control" value="{{ old('google_reviews_url') }}" placeholder="https://g.page/...">
                    </div>
                </div>
            </div>
        </div>

        {{-- الصور --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-images"></i>
                {{ __('app.images') }}
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_logo') }}</label>
                        <div class="image-upload-zone" id="logoUploadZone">
                            <div class="image-preview" id="logoPreview" style="display: none;">
                                <img id="logoPreviewImg" src="" alt="Logo Preview">
                                <button type="button" class="remove-image-btn" onclick="removeImage('logo')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="upload-placeholder" id="logoPlaceholder">
                                <i class="fas fa-image"></i>
                                <span>{{ app()->getLocale() === 'ar' ? 'اختر شعار المطعم' : 'Choose restaurant logo' }}</span>
                                <small>PNG, JPG - {{ __('app.max_size') }}: 2MB</small>
                                <small style="color: var(--primary); margin-top: 4px;"><i class="fas fa-info-circle"></i> {{ app()->getLocale() === 'ar' ? 'الأبعاد المثالية: 500×500 بكسل' : 'Ideal size: 500×500 pixels' }}</small>
                            </div>
                            <input type="file" name="logo" id="logoInput" accept="image/*" onchange="previewImage(this, 'logo')">
                        </div>
                        @error('logo')<div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.restaurant_cover') }}</label>
                        <div class="image-upload-zone cover" id="coverUploadZone">
                            <div class="image-preview" id="coverPreview" style="display: none;">
                                <img id="coverPreviewImg" src="" alt="Cover Preview">
                                <button type="button" class="remove-image-btn" onclick="removeImage('cover')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="upload-placeholder" id="coverPlaceholder">
                                <i class="fas fa-panorama"></i>
                                <span>{{ app()->getLocale() === 'ar' ? 'اختر صورة الغلاف' : 'Choose cover image' }}</span>
                                <small>PNG, JPG - {{ __('app.max_size') }}: 2MB</small>
                                <small style="color: var(--primary); margin-top: 4px;"><i class="fas fa-info-circle"></i> {{ app()->getLocale() === 'ar' ? 'الأبعاد المثالية: 1200×600 بكسل' : 'Ideal size: 1200×600 pixels' }}</small>
                            </div>
                            <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="previewImage(this, 'cover')">
                        </div>
                        @error('cover_image')<div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- نوع المنيو --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-utensils"></i>
                {{ app()->getLocale() === 'ar' ? 'نوع المنيو' : 'Menu Type' }}
            </div>
            <div class="form-card-body">
                <div class="menu-type-selection">
                    <label class="menu-type-option active" onclick="selectMenuType('digital')">
                        <input type="radio" name="menu_type" value="digital" checked>
                        <div class="menu-type-icon digital">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div class="menu-type-info">
                            <h6>{{ app()->getLocale() === 'ar' ? 'منيو إلكتروني' : 'Digital Menu' }}</h6>
                            <p>{{ app()->getLocale() === 'ar' ? 'أضف الأصناف والأسعار بشكل تفاعلي' : 'Add items and prices interactively' }}</p>
                        </div>
                    </label>

                    <label class="menu-type-option" onclick="selectMenuType('pdf')">
                        <input type="radio" name="menu_type" value="pdf">
                        <div class="menu-type-icon pdf">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="menu-type-info">
                            <h6>{{ app()->getLocale() === 'ar' ? 'منيو PDF' : 'PDF Menu' }}</h6>
                            <p>{{ app()->getLocale() === 'ar' ? 'ارفع ملف PDF جاهز للمنيو' : 'Upload a ready PDF menu file' }}</p>
                        </div>
                    </label>
                </div>

                {{-- قسم رفع PDF --}}
                <div class="pdf-upload-section" id="pdfUploadSection" style="display: none; margin-top: 20px;">
                    <div class="pdf-upload-zone" id="pdfUploadZone">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h5>{{ app()->getLocale() === 'ar' ? 'اسحب الملف هنا أو اضغط للاختيار' : 'Drag file here or click to browse' }}</h5>
                        <p>{{ app()->getLocale() === 'ar' ? 'ملفات PDF فقط' : 'PDF files only' }}</p>
                        <div class="file-info">
                            <span><i class="fas fa-file"></i> PDF</span>
                            <span><i class="fas fa-weight-hanging"></i> {{ __('app.pdf_max_size') }}</span>
                        </div>
                        <input type="file" name="menu_pdf" id="pdfFileInput" accept="application/pdf">
                        <div class="selected-file" id="selectedFile">
                            <i class="fas fa-check-circle"></i>
                            <span id="selectedFileName"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الإعدادات --}}
        <div class="form-card">
            <div class="form-card-header-alt">
                <i class="fas fa-cog"></i>
                {{ __('app.settings') }}
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('app.currency') }}</label>
                        <select name="currency" id="currencySelect" class="form-select" onchange="updateCurrencySymbol()">
                            <option value="SAR" data-symbol="ر.س" {{ old('currency') == 'SAR' ? 'selected' : '' }}>{{ __('app.currency_sar') }}</option>
                            <option value="AED" data-symbol="د.إ" {{ old('currency') == 'AED' ? 'selected' : '' }}>{{ __('app.currency_aed') }}</option>
                            <option value="KWD" data-symbol="د.ك" {{ old('currency') == 'KWD' ? 'selected' : '' }}>{{ __('app.currency_kwd') }}</option>
                            <option value="JOD" data-symbol="د.أ" {{ old('currency', 'JOD') == 'JOD' ? 'selected' : '' }}>{{ __('app.currency_jod') }}</option>
                            <option value="EGP" data-symbol="ج.م" {{ old('currency') == 'EGP' ? 'selected' : '' }}>{{ __('app.currency_egp') }}</option>
                            <option value="USD" data-symbol="$" {{ old('currency') == 'USD' ? 'selected' : '' }}>{{ __('app.currency_usd') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('app.currency_symbol') }}</label>
                        <input type="text" name="currency_symbol" id="currencySymbol" class="form-control" value="{{ old('currency_symbol', 'د.أ') }}" readonly style="background: #f8fafc;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('app.primary_color') }}</label>
                        <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', '#FF6B35') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('app.secondary_color') }}</label>
                        <input type="color" name="secondary_color" class="form-control form-control-color w-100" value="{{ old('secondary_color', '#1e293b') }}">
                    </div>
                </div>
                
                <div class="switch-wrapper" style="margin-top: 0;">
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked>
                    </div>
                    <label for="is_active">{{ __('app.restaurant_active') }}</label>
                </div>
            </div>
        </div>

        {{-- الأزرار --}}
        <div class="form-actions spread">
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                {{ __('app.back') }}
            </a>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                {{ __('app.create_restaurant') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const restaurantsData = @json($mainRestaurants->keyBy('id'));

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('parent_id')) {
        document.querySelector('input[value="branch"]').checked = true;
        toggleParentSelect();
        onParentChange();
    }
    updateCurrencySymbol();
});

function toggleParentSelect() {
    const isBranch = document.querySelector('input[value="branch"]').checked;
    const parentSelect = document.getElementById('parent-select');
    const parentInput = document.getElementById('parent_id');
    
    if (isBranch) {
        parentSelect.classList.add('show');
        parentInput.required = true;
    } else {
        parentSelect.classList.remove('show');
        parentInput.required = false;
        parentInput.value = '';
    }
}

function onParentChange() {
    const parentId = document.getElementById('parent_id').value;
    if (parentId && restaurantsData[parentId]) {
        const parent = restaurantsData[parentId];
        document.getElementById('currencySelect').value = parent.currency || 'JOD';
        updateCurrencySymbol();
        document.querySelector('[name="primary_color"]').value = parent.primary_color || '#FF6B35';
        document.querySelector('[name="secondary_color"]').value = parent.secondary_color || '#1e293b';
    }
}

function updateCurrencySymbol() {
    const select = document.getElementById('currencySelect');
    const symbolInput = document.getElementById('currencySymbol');
    const selectedOption = select.options[select.selectedIndex];
    symbolInput.value = selectedOption.getAttribute('data-symbol');
}

document.getElementById('parent_id').addEventListener('change', onParentChange);

// Menu Type Selection
function selectMenuType(type) {
    document.querySelectorAll('.menu-type-option').forEach(opt => opt.classList.remove('active'));
    const selected = document.querySelector('input[name="menu_type"][value="' + type + '"]');
    if (selected) {
        selected.checked = true;
        selected.closest('.menu-type-option').classList.add('active');
    }

    const pdfSection = document.getElementById('pdfUploadSection');
    if (type === 'pdf') {
        pdfSection.style.display = 'block';
    } else {
        pdfSection.style.display = 'none';
    }
}

// PDF Upload Zone
const pdfZone = document.getElementById('pdfUploadZone');
const pdfInput = document.getElementById('pdfFileInput');
const selectedFile = document.getElementById('selectedFile');
const selectedFileName = document.getElementById('selectedFileName');

if (pdfZone && pdfInput) {
    ['dragenter', 'dragover'].forEach(event => {
        pdfZone.addEventListener(event, (e) => {
            e.preventDefault();
            pdfZone.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        pdfZone.addEventListener(event, (e) => {
            e.preventDefault();
            pdfZone.classList.remove('drag-over');
        });
    });

    pdfZone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length && files[0].type === 'application/pdf') {
            pdfInput.files = files;
            showSelectedFile(files[0].name);
        }
    });

    pdfInput.addEventListener('change', function() {
        if (this.files.length) {
            showSelectedFile(this.files[0].name);
        }
    });

    function showSelectedFile(name) {
        selectedFileName.textContent = name;
        selectedFile.classList.add('show');
    }
}

// Image Preview Functions
function previewImage(input, type) {
    const preview = document.getElementById(type + 'Preview');
    const previewImg = document.getElementById(type + 'PreviewImg');
    const placeholder = document.getElementById(type + 'Placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(type) {
    const input = document.getElementById(type + 'Input');
    const preview = document.getElementById(type + 'Preview');
    const previewImg = document.getElementById(type + 'PreviewImg');
    const placeholder = document.getElementById(type + 'Placeholder');

    input.value = '';
    previewImg.src = '';
    preview.style.display = 'none';
    placeholder.style.display = 'flex';
}
</script>
@endpush