<x-user-layout>
   <main class="w-full mx-auto h-auto bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
       <!-- Header -->
       <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
           <button wire:navigate href="{{ route('receive.payid') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
               <i class="fas fa-arrow-left text-xl"></i>
           </button>
           <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Receive {{ $asset['name'] }}</h1>
           <div class="w-8"></div>
       </div>

       <!-- Main Content -->
       <div class="p-4 space-y-8">
           <!-- Warning Banner -->
           <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-700 rounded-xl p-4">
               <div class="flex items-start space-x-3">
                   <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5"></i>
                   <p class="text-gray-700 dark:text-gray-300 text-sm">
                       Only send {{ $asset['symbol'] }} {{ $asset['network'] ? '(' . $asset['network'] . ')' : '' }} assets from a CovaChain Wallet Pay ID to your CovaChain Wallet Pay ID.
                   </p>
               </div>
           </div>

           <!-- Pay ID Section -->
           <div class="flex items-center justify-center space-x-3">
               <div class="relative">
                   @if($asset['icon_url'])
                       <img src="{{ $asset['icon_url'] }}" alt="{{ $asset['symbol'] }}" class="w-10 h-10 rounded-full">
                       @if(!empty($asset['network_url']))
                           <img src="{{ $asset['network_url'] }}" 
                               alt="{{ $asset['network'] }}" 
                               class="absolute border-2 border-white dark:border-gray-800" 
                               style="width: 14px; height: auto; bottom: 0; right: 0; background: white; border-radius: 50%;">
                       @endif
                   @else
                       <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                           <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400 text-sm"></i>
                       </div>
                   @endif
               </div>
               <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $asset['symbol'] }}</span>
               @if($asset['network'])
                   <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded-full">{{ $asset['network'] }}</span>
               @endif
           </div>

           <!-- QR Code Container -->
           <div class="flex flex-col items-center space-y-4">
               <div class="relative">
                   <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                       <div id="qrcode" class="w-64 h-64 relative">
                           <!-- QR code will be generated here -->
                           <div id="qrCanvas"></div>
                           <!-- Centered logo overlay -->
                           <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                               <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400 text-xl"></i>
                            </div>
                           </div>
                       </div>
                   </div>
               </div>
               <!-- Pay ID Text -->
               <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 w-full max-w-md">
                  <p class="text-gray-800 dark:text-white font-mono text-center select-all break-all" id="payId">{{ $user->uuid }}</p>
               </div>
           </div>

           <!-- Action Buttons -->
           <div class="flex justify-center space-x-16 pt-4">
               <!-- Copy Button -->
               <button id="copyPayIdBtn" onclick="copyPayId()" class="flex flex-col items-center space-y-2 text-gray-600 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                   <div class="w-14 h-14 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center shadow-sm">
                       <i class="far fa-copy text-xl"></i>
                   </div>
                   <span class="text-sm font-medium">Copy</span>
               </button>

               <!-- Share Button -->
               <button onclick="sharePayId()" class="flex flex-col items-center space-y-2 text-gray-600 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                   <div class="w-14 h-14 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center shadow-sm">
                       <i class="fas fa-share-alt text-xl"></i>
                   </div>
                   <span class="text-sm font-medium">Share</span>
               </button>
           </div>
            <!-- Copy Success Message -->
               <div id="copyMessage" class="fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg transform translate-y-[-100%] opacity-0 transition-all duration-300">
               PayID copied to clipboard
           </div>
       </div>
   </main>

   @push('scripts')
   <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const qrData = {
               payId: '{{ $user->uuid }}',
               symbol: '{{ $asset["symbol"] }}',
               network: '{{ $asset["network"] ?? "native" }}',
               type: 'LC_PAYMENT'
           };

           new QRCode(document.getElementById("qrCanvas"), {
               text: JSON.stringify(qrData),
               width: 256,
               height: 256,
               colorDark: "#000000",
               colorLight: "#FFFFFF",
               correctLevel: QRCode.CorrectLevel.H
           });
       });

       function copyPayId() {
           const payId = document.getElementById('payId').textContent;
           const copyBtn = document.getElementById('copyPayIdBtn');
           const originalHTML = copyBtn.innerHTML;
           
           navigator.clipboard.writeText(payId)
               .then(() => {
                   // Update button to success state with dark mode support
                   copyBtn.innerHTML = `
                       <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-full flex items-center justify-center shadow-sm">
                           <i class="fas fa-check text-green-600 dark:text-green-400 text-xl"></i>
                       </div>
                       <span class="text-sm font-medium text-green-600 dark:text-green-400">Copied!</span>
                   `;
                   
                   // Show success message
                   const message = document.getElementById('copyMessage');
                   message.style.transform = 'translate(0, 0)';
                   message.style.opacity = '1';
                   
                   // Revert button after 2 seconds
                   setTimeout(() => {
                       copyBtn.innerHTML = originalHTML;
                   }, 2000);
                   
                   // Hide message after 3 seconds
                   setTimeout(() => {
                       message.style.transform = 'translate(0, -100%)';
                       message.style.opacity = '0';
                   }, 3000);
               })
               .catch(err => {
                   console.error('Failed to copy text: ', err);
                   alert('Failed to copy PayID. Please select and copy manually.');
               });
       }

       function sharePayId() {
           const payId = document.getElementById('payId').textContent;
           const assetSymbol = '{{ $asset['symbol'] }}';
           const assetNetwork = '{{ $asset['network'] }}';
           const networkText = assetNetwork ? ` (${assetNetwork})` : '';
           const shareText = `My CovaChain Wallet PayID for ${assetSymbol}${networkText}: ${payId}\n\nSent via CovaChain Wallet`;
           
           // Try Web Share API first
           if (navigator.share) {
               navigator.share({
                   title: 'My PayID',
                   text: shareText,
                   url: window.location.href
               }).catch(err => {
                   console.log('Web Share failed, falling back to WhatsApp');
                   shareViaWhatsApp(shareText);
               });
           } else {
               shareViaWhatsApp(shareText);
           }
       }
       
       function shareViaWhatsApp(text) {
           const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
           window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
       }
   </script>
   @endpush
</x-user-layout>