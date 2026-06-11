/**
 * QR Menu - Admin Shared Components JS
 * Version: 2.0
 * 
 * هذا الملف يحتوي على كل الـ JavaScript المشترك بين صفحات الأدمن
 */

// ==========================================
// Icon Preview
// مستخدم في: categories create/edit
// ==========================================
function initIconPreview() {
    const select = document.getElementById('icon-select');
    const preview = document.getElementById('icon-preview');
    
    if (select && preview) {
        select.addEventListener('change', function() {
            preview.className = this.value || 'fas fa-folder';
        });
    }
}

// ==========================================
// Image Preview
// مستخدم في: categories, items, restaurants, staff
// ==========================================
function initImagePreview() {
    const input = document.getElementById('image-input');
    const preview = document.getElementById('image-preview');
    
    if (input && preview) {
        input.addEventListener('change', function() {
            const img = preview.querySelector('img');
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.add('show');
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.classList.remove('show');
            }
        });
    }
}

// ==========================================
// Beautiful Delete Modal
// ==========================================
const DeleteModal = {
    modal: null,
    form: null,
    resolvePromise: null,
    
    init() {
        // إنشاء الـ Modal إذا مش موجود
        if (!document.getElementById('deleteModal')) {
            this.createModal();
        }
        this.modal = document.getElementById('deleteModal');
        this.bindEvents();
    },
    
    createModal() {
        const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
        
        const modalHTML = `
        <div id="deleteModal" class="delete-modal-overlay">
            <div class="delete-modal">
                <div class="delete-modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
                    </svg>
                </div>
                <h3 class="delete-modal-title">${isRtl ? 'تأكيد الحذف' : 'Confirm Delete'}</h3>
                <p class="delete-modal-message" id="deleteModalMessage">${isRtl ? 'هل أنت متأكد من حذف هذا العنصر؟' : 'Are you sure you want to delete this item?'}</p>
                <p class="delete-modal-warning">${isRtl ? 'لا يمكن التراجع عن هذا الإجراء' : 'This action cannot be undone'}</p>
                <div class="delete-modal-actions">
                    <button type="button" class="delete-modal-btn cancel" id="deleteModalCancel">
                        ${isRtl ? 'إلغاء' : 'Cancel'}
                    </button>
                    <button type="button" class="delete-modal-btn confirm" id="deleteModalConfirm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        </svg>
                        ${isRtl ? 'نعم، احذف' : 'Yes, Delete'}
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .delete-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .delete-modal {
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .delete-modal-overlay.show .delete-modal {
            transform: scale(1) translateY(0);
        }
        
        .delete-modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse-icon 2s ease-in-out infinite;
        }
        
        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .delete-modal-icon svg {
            width: 40px;
            height: 40px;
            color: #dc2626;
        }
        
        .delete-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        
        .delete-modal-message {
            color: #475569;
            font-size: 1rem;
            margin-bottom: 8px;
            line-height: 1.6;
        }
        
        .delete-modal-warning {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .delete-modal-warning::before {
            content: '⚠️';
            font-size: 0.9rem;
        }
        
        .delete-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        
        .delete-modal-btn {
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            min-width: 120px;
            justify-content: center;
        }
        
        .delete-modal-btn.cancel {
            background: #f1f5f9;
            color: #64748b;
        }
        
        .delete-modal-btn.cancel:hover {
            background: #e2e8f0;
            color: #475569;
        }
        
        .delete-modal-btn.confirm {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        
        .delete-modal-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        .delete-modal-btn.confirm:active {
            transform: translateY(0);
        }
        
        /* Animation for shake on hover */
        .delete-modal-icon:hover {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        </style>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    },
    
    bindEvents() {
        document.getElementById('deleteModalCancel').addEventListener('click', () => this.hide(false));
        document.getElementById('deleteModalConfirm').addEventListener('click', () => this.hide(true));
        
        // إغلاق بالـ Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('show')) {
                this.hide(false);
            }
        });
        
        // إغلاق بالضغط خارج الـ Modal
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide(false);
            }
        });
    },
    
    show(message, formElement) {
        this.form = formElement;
        
        if (message) {
            document.getElementById('deleteModalMessage').textContent = message;
        }
        
        this.modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        return new Promise((resolve) => {
            this.resolvePromise = resolve;
        });
    },
    
    hide(confirmed) {
        this.modal.classList.remove('show');
        document.body.style.overflow = '';
        
        if (this.resolvePromise) {
            this.resolvePromise(confirmed);
        }
        
        if (confirmed && this.form) {
            this.form.submit();
        }
    }
};

// ==========================================
// Delete Confirmation Function (البديل الجديد)
// ==========================================
function confirmDelete(message, formElement) {
    // إذا تم تمرير form مباشرة
    if (formElement) {
        event.preventDefault();
        DeleteModal.show(message, formElement);
        return false;
    }
    
    // للتوافق مع الكود القديم - يرجع true/false
    const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
    const msg = message || (isRtl ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?');
    return confirm(msg);
}

// ==========================================
// دالة جديدة للحذف مع Modal
// استخدام: onclick="deleteWithModal(event, 'رسالة الحذف')"
// ==========================================
function deleteWithModal(event, message) {
    event.preventDefault();
    const form = event.target.closest('form');
    DeleteModal.show(message, form);
}

// ==========================================
// Auto Initialize on Page Load
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    initIconPreview();
    initImagePreview();
    DeleteModal.init();
    
    // Auto-bind delete forms
    document.querySelectorAll('form[data-delete-modal]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = this.dataset.deleteMessage || null;
            DeleteModal.show(message, this);
        });
    });
    
    // Auto-bind delete buttons with data attribute
    document.querySelectorAll('[data-delete-confirm]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.deleteConfirm;
            const form = this.closest('form') || document.getElementById(this.dataset.formId);
            if (form) {
                DeleteModal.show(message, form);
            }
        });
    });
});