<x-user-layout :title="'KYC Verification'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white text-gray-900 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1">KYC Verification</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 mb-4 text-red-500 px-4 py-3 rounded-xl relative" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif
        
        <!-- Dynamic Info Banner -->
        <div id="kyc-info-banner" class="bg-blue-50 rounded-xl p-4 mb-6 
             {{ auth()->user()->kyc_status !== 'not_submitted' ? 'hidden' : '' }}">
            <p class="text-blue-500 text-center">Upload your documents to verify your account.</p>
        </div>
        
        <div id="kyc-reject-banner" class="bg-red-50 rounded-xl p-4 mb-6 
             {{ auth()->user()->kyc_status !== 'rejected' ? 'hidden' : '' }}">
            <div class="flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <p class="text-red-500">KYC Rejected. Please reupload your documents.</p>
            </div>
        </div>

        <!-- Pending State -->
        <div id="kyc-pending-banner" class="bg-blue-50 rounded-xl p-4 mb-6 
            {{ auth()->user()->kyc_status !== 'pending' ? 'hidden' : '' }}">
            <div class="flex items-center justify-center">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                <p class="text-blue-500">Your documents are under review. Verification pending...</p>
            </div>
            <div class="mt-3 text-center text-sm text-gray-500">
                Submitted on: {{ auth()->user()->updated_at->format('M d, Y H:i') }}
            </div>
        </div>

        <!-- Approved State -->
        <div id="kyc-approved-banner" class="bg-green-50 rounded-xl p-4 mb-6 
            {{ auth()->user()->kyc_status !== 'approved' ? 'hidden' : '' }}">
            <div class="flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <p class="text-green-500">Your account has been verified successfully!</p>
            </div>
            <div class="mt-3 text-center text-sm text-gray-500">
                Verified on: {{ auth()->user()->updated_at->format('M d, Y H:i') }}
            </div>
        </div>

        <!-- Document Upload Form -->
        <form id="kyc-form" method="POST" action="{{ route('user.kyc.upload') }}" 
              enctype="multipart/form-data"
              class="{{ in_array(auth()->user()->kyc_status, ['pending', 'approved']) ? 'hidden' : '' }}">
            @csrf
            <!-- Document Upload Sections -->
            <div class="space-y-6">
                <!-- ID Front Section -->
                <div class="space-y-3 bg-white rounded-xl shadow-sm border border-gray-100 p-4" id="front-id-section">
                    <h2 class="text-lg font-medium">ID (front)</h2>
                    <p class="text-sm text-gray-500">This can be a photo of the front of your: Driving license, National identity card, Passport, Birth certificate.</p>
                    
                    <!-- Upload Box -->
                    <div class="relative border-2 border-dashed border-gray-200 rounded-lg p-6 text-center cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition-colors">
                        <input type="file" class="hidden" id="front_id" name="front_id" accept=".jpg,.jpeg,.png,.pdf" required>
                        <label for="front_id" class="cursor-pointer block">
                            <div class="upload-content">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-id-card text-2xl text-blue-500"></i>
                                </div>
                                <p class="text-sm text-gray-500">Drop file here or tap to upload</p>
                            </div>
                            <div class="preview-container mt-4 hidden">
                                <img src="#" alt="Preview" class="preview-image max-h-32 mx-auto rounded" />
                                <div class="mt-2 text-sm text-gray-500"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- ID Back Section -->
                <div class="space-y-3 bg-white rounded-xl shadow-sm border border-gray-100 p-4" id="back-id-section">
                    <h2 class="text-lg font-medium">ID (back)</h2>
                    <p class="text-sm text-gray-500">This should be a photo of the back of any above ID card you already uploaded.</p>
                    
                    <div class="relative border-2 border-dashed border-gray-200 rounded-lg p-6 text-center cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition-colors">
                        <input type="file" class="hidden" id="back_id" name="back_id" accept=".jpg,.jpeg,.png,.pdf" required>
                        <label for="back_id" class="cursor-pointer block">
                            <div class="upload-content">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-id-card text-2xl text-blue-500"></i>
                                </div>
                                <p class="text-sm text-gray-500">Drop file here or tap to upload</p>
                            </div>
                            <div class="preview-container mt-4 hidden">
                                <img src="#" alt="Preview" class="preview-image max-h-32 mx-auto rounded" />
                                <div class="mt-2 text-sm text-gray-500"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Proof of Residence Section -->
                <div class="space-y-3 bg-white rounded-xl shadow-sm border border-gray-100 p-4" id="residence-section">
                    <h2 class="text-lg font-medium">Proof of Residence</h2>
                    <p class="text-sm text-gray-500">This can be your: Water bill, Electricity bill, Telephone bill, Internet bill, Credit card bill or Statement, Bank statement.</p>
                    
                    <div class="relative border-2 border-dashed border-gray-200 rounded-lg p-6 text-center cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition-colors">
                        <input type="file" class="hidden" id="proof_of_residence" name="proof_of_residence" accept=".jpg,.jpeg,.png,.pdf" required>
                        <label for="proof_of_residence" class="cursor-pointer block">
                            <div class="upload-content">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-file-alt text-2xl text-blue-500"></i>
                                </div>
                                <p class="text-sm text-gray-500">Drop file here or tap to upload</p>
                            </div>
                            <div class="preview-container mt-4 hidden">
                                <img src="#" alt="Preview" class="preview-image max-h-32 mx-auto rounded" />
                                <div class="mt-2 text-sm text-gray-500"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3.5 px-4 rounded-xl transition-colors">
                    Submit for Verification
                </button>
            </div>
        </form>

        <!-- Status Message -->
        <div class="mt-4 text-center text-sm text-gray-500 {{ in_array(auth()->user()->kyc_status, ['pending', 'approved']) ? 'hidden' : '' }}">
            Average verification time: 24-48 hours
        </div>

        <!-- Success/Error Modal -->
        <div id="kyc-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 animate-modal-show">
                <div id="modal-icon-success" class="hidden">
                    <div class="mx-auto mb-6 text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-check-circle text-5xl text-green-500"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-medium text-gray-900 text-center mb-3">Submission Successful</h2>
                    <p class="text-gray-500 text-center mb-6">
                        Your KYC documents have been submitted successfully. 
                        Our team will review your documents shortly.
                    </p>
                </div>
                <div id="modal-icon-error" class="hidden">
                    <div class="mx-auto mb-6 text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-times-circle text-5xl text-red-500"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-medium text-gray-900 text-center mb-3">Submission Failed</h2>
                    <p id="error-message" class="text-gray-500 text-center mb-6">
                        There was an error submitting your documents. Please try again.
                    </p>
                </div>
                <button id="modal-close-btn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3.5 px-4 rounded-xl transition-colors">
                    Close
                </button>
            </div>
        </div>
    </main>

    <style>
    .animate-modal-show {
        animation: modalShow 0.3s ease-out;
    }
    
    @keyframes modalShow {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('kyc-form');
        const modal = document.getElementById('kyc-modal');
        const modalSuccessIcon = document.getElementById('modal-icon-success');
        const modalErrorIcon = document.getElementById('modal-icon-error');
        const modalCloseBtn = document.getElementById('modal-close-btn');
        const errorMessageEl = document.getElementById('error-message');

        // Close modal and reload the page
        modalCloseBtn.addEventListener('click', function() {
            // Add exit animation
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.style.opacity = '1';
                window.location.reload();
            }, 300);
        });

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';

            try {
                const response = await fetch('{{ route("user.kyc.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                // Reset modal states
                modalSuccessIcon.classList.add('hidden');
                modalErrorIcon.classList.add('hidden');

                if (result.status === 'success') {
                    // Show success modal
                    modalSuccessIcon.classList.remove('hidden');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // Update page to pending state
                    document.getElementById('kyc-info-banner').classList.add('hidden');
                    document.getElementById('kyc-form').classList.add('hidden');
                    document.getElementById('kyc-pending-banner').classList.remove('hidden');
                } else {
                    // Show error modal
                    errorMessageEl.textContent = result.message || 'There was an error submitting your documents. Please try again.';
                    modalErrorIcon.classList.remove('hidden');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Show error modal
                errorMessageEl.textContent = 'An unexpected error occurred. Please try again.';
                modalErrorIcon.classList.remove('hidden');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit for Verification';
            }
        });

        // Fetch initial document status on page load
        async function fetchDocumentStatus() {
            try {
                const response = await fetch('{{ route("user.kyc.status") }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                // Hide all banners and form first
                ['kyc-info-banner', 'kyc-reject-banner', 'kyc-pending-banner', 'kyc-approved-banner', 'kyc-form'].forEach(id => {
                    document.getElementById(id).classList.add('hidden');
                });

                // Show appropriate banner based on status
                switch(data.status) {
                    case 'not_submitted':
                        document.getElementById('kyc-info-banner').classList.remove('hidden');
                        document.getElementById('kyc-form').classList.remove('hidden');
                        break;
                    case 'rejected':
                        document.getElementById('kyc-reject-banner').classList.remove('hidden');
                        document.getElementById('kyc-form').classList.remove('hidden');
                        break;   
                    case 'pending':
                        document.getElementById('kyc-pending-banner').classList.remove('hidden');
                        break;
                    case 'approved':
                        document.getElementById('kyc-approved-banner').classList.remove('hidden');
                        break;
                }
            } catch (error) {
                console.error('Error fetching status:', error);
            }
        }

        // Initial status fetch
        fetchDocumentStatus();

        // Function to handle file previews
        function handleFilePreview(inputId) {
            const fileInput = document.getElementById(inputId);
            const uploadBox = fileInput.closest('.relative');
            const previewContainer = uploadBox.querySelector('.preview-container');
            const previewImage = previewContainer.querySelector('.preview-image');
            const previewText = previewContainer.querySelector('.text-sm');
            
            // Add ripple effect
            uploadBox.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.width = '10px';
                ripple.style.height = '10px';
                ripple.style.borderRadius = '50%';
                ripple.style.backgroundColor = 'rgba(0, 112, 243, 0.1)';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.transform = 'translate(-50%, -50%) scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const fileType = file.type;
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (validImageTypes.includes(fileType)) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.alt = file.name;
                            previewContainer.querySelector('.text-sm').textContent = file.name;
                            previewContainer.classList.remove('hidden');
                            uploadBox.querySelector('.upload-content').classList.add('hidden');
                            
                            // Add file selected styling
                            uploadBox.classList.add('border-blue-500');
                            uploadBox.classList.add('bg-blue-50/50');
                        }
                        reader.readAsDataURL(file);
                    } else if (fileType === 'application/pdf') {
                        previewImage.src = '{{ asset("images/pdf-icon.png") }}'; // Ensure you have a PDF icon in your public/images directory
                        previewImage.alt = 'PDF Icon';
                        previewContainer.querySelector('.text-sm').textContent = file.name;
                        previewContainer.classList.remove('hidden');
                        uploadBox.querySelector('.upload-content').classList.add('hidden');
                        
                        // Add file selected styling
                        uploadBox.classList.add('border-blue-500');
                        uploadBox.classList.add('bg-blue-50/50');
                    } else {
                        // Unsupported file type
                        alert('Unsupported file type. Please upload an image or PDF.');
                        fileInput.value = ''; // Reset the input
                        previewContainer.classList.add('hidden');
                        uploadBox.querySelector('.upload-content').classList.remove('hidden');
                    }
                } else {
                    // No file selected
                    previewContainer.classList.add('hidden');
                    uploadBox.querySelector('.upload-content').classList.remove('hidden');
                    
                    // Remove file selected styling
                    uploadBox.classList.remove('border-blue-500');
                    uploadBox.classList.remove('bg-blue-50/50');
                }
            });
        }

        // Initialize file preview handlers for each input
        ['front_id', 'back_id', 'proof_of_residence'].forEach(inputId => {
            handleFilePreview(inputId);
        });
    });
    
    // Add ripple animation
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes ripple {
                to {
                    transform: translate(-50%, -50%) scale(20);
                    opacity: 0;
                }
            }
        </style>
    `);
    </script>
    @endpush
</x-user-layout>
