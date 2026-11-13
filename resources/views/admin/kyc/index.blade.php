<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="flex justify-between items-center my-6">
            <h2 class="text-2xl font-semibold text-gray-700">KYC Verification</h2>
            <div class="flex space-x-3">
                <select id="status-filter" class="border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </div>

        {{-- Flash Messages --}}
        <x-session-status class="mb-4" :status="session('status')" />

        {{-- KYC Submissions Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3">User Info</th>
                        <th class="px-4 py-3">Documents</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted Date</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($users as $user)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="mr-3 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-lg font-medium text-purple-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $user->uuid }}</p>
                                    </div>
                                </div>
                            </td>
                          <td class="px-4 py-3">
    <div class="flex flex-col space-y-2">
        @if($user->front_id)
            <button type="button" 
                    onclick="openImageModal('{{ asset('public/' . $user->front_id) }}')"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <i class="ri-image-line mr-1"></i>
                Front ID
            </button>
        @endif
        
        @if($user->back_id)
            <button type="button"
                    onclick="openImageModal('{{ asset('public/' . $user->back_id) }}')"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <i class="ri-image-line mr-1"></i>
                Back ID
            </button>
        @endif
        
        @if($user->proof_of_residence)
            <button type="button"
                    onclick="openImageModal('{{ asset('public/' . $user->proof_of_residence) }}')"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <i class="ri-image-line mr-1"></i>
                Proof of Residence
            </button>
        @endif
    </div>
</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($user->kyc_status === 'approved') bg-green-100 text-green-800
                                    @elseif($user->kyc_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->kyc_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($user->kyc_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user->updated_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($user->kyc_status === 'pending')
                                    <div class="flex space-x-2">
                                        {{-- Approve Button --}}
                                        <form action="{{ route('admin.kyc.update', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Reject Button --}}
                                        <button type="button"
                                                onclick="openRejectModal('{{ $user->id }}')"
                                                class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No KYC submissions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-4 py-3 border-t">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Image Preview Modal --}}
   <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg w-11/12 md:w-3/4 lg:w-2/3 xl:max-w-4xl">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-medium">Document Preview</h3>
                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div class="p-4 max-h-[calc(100vh-12rem)] overflow-auto">
                <img id="previewImage" src="" alt="Document Preview" class="max-w-full h-auto mx-auto">
            </div>
        </div>
    </div>
</div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reject KYC Submission</h3>
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason
                            </label>
                            <textarea name="rejection_reason" 
                                      required
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                      placeholder="Please provide a reason for rejection..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRejectModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image Modal Functions
        function openImageModal(imageUrl) {
            document.getElementById('previewImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Reject Modal Functions
        function openRejectModal(submissionId) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/kyc/${submissionId}`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Status Filter
        document.getElementById('status-filter').addEventListener('change', function() {
            const status = this.value;
            const currentUrl = new URL(window.location.href);
            if (status) {
                currentUrl.searchParams.set('status', status);
            } else {
                currentUrl.searchParams.delete('status');
            }
            window.location.href = currentUrl.toString();
        });
    </script>
</x-admin-layout>