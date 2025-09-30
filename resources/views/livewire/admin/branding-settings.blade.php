<div>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Branding Settings</h1>
            <p class="text-gray-600">Customize your website's appearance and branding</p>
        </div>


        <form wire:submit.prevent="save" class="space-y-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i data-feather="info" class="w-5 h-5 mr-2 text-primary-600"></i>
                    Basic Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Shop Name -->
                    <div class="md:col-span-2">
                        <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Shop Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="shop_name"
                               wire:model="shop_name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('shop_name') border-red-500 @enderror"
                               placeholder="Enter your shop name">
                        @error('shop_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description"
                                  wire:model="description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of your shop"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email
                        </label>
                        <input type="email" 
                               id="contact_email"
                               wire:model="contact_email" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('contact_email') border-red-500 @enderror"
                               placeholder="contact@example.com">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone
                        </label>
                        <input type="text" 
                               id="contact_phone"
                               wire:model="contact_phone" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('contact_phone') border-red-500 @enderror"
                               placeholder="+60123456789">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea id="address"
                                  wire:model="address" 
                                  rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('address') border-red-500 @enderror"
                                  placeholder="Your business address"></textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Logo & Favicon -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i data-feather="image" class="w-5 h-5 mr-2 text-primary-600"></i>
                    Logo & Favicon
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Shop Logo
                        </label>
                        
                        <!-- Current Logo Preview -->
                        <div class="mb-4">
                            <div class="w-32 h-32 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                <img src="{{ $current_logo_url }}" 
                                     alt="Current Logo" 
                                     class="max-w-full max-h-full object-contain">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Current logo</p>
                        </div>

                        <!-- Logo Upload -->
                        <div class="space-y-3">
                            <input type="file" 
                                   wire:model="logo" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('logo') border-red-500 @enderror">
                            @error('logo')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($logo)
                                <div class="text-sm text-gray-600">
                                    <p>Selected: {{ $logo->getClientOriginalName() }}</p>
                                    <p>Size: {{ number_format($logo->getSize() / 1024, 2) }} KB</p>
                                </div>
                            @endif

                            <button type="button" 
                                    wire:click="removeLogo"
                                    class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                Remove current logo
                            </button>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Favicon
                        </label>
                        
                        <!-- Current Favicon Preview -->
                        <div class="mb-4">
                            <div class="w-16 h-16 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                <img src="{{ $current_favicon_url }}" 
                                     alt="Current Favicon" 
                                     class="max-w-full max-h-full object-contain">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Current favicon</p>
                        </div>

                        <!-- Favicon Upload -->
                        <div class="space-y-3">
                            <input type="file" 
                                   wire:model="favicon" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('favicon') border-red-500 @enderror">
                            @error('favicon')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($favicon)
                                <div class="text-sm text-gray-600">
                                    <p>Selected: {{ $favicon->getClientOriginalName() }}</p>
                                    <p>Size: {{ number_format($favicon->getSize() / 1024, 2) }} KB</p>
                                </div>
                            @endif

                            <button type="button" 
                                    wire:click="removeFavicon"
                                    class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                Remove current favicon
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-feather="info" class="w-5 h-5 text-blue-600 mr-2 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Image Guidelines:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• Logo: Recommended size 200x200px, max 2MB</li>
                                <li>• Favicon: Recommended size 32x32px, max 1MB</li>
                                <li>• Supported formats: JPG, PNG, GIF, SVG</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i data-feather="share-2" class="w-5 h-5 mr-2 text-primary-600"></i>
                    Social Media Links
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Facebook -->
                    <div>
                        <label for="social_links.facebook" class="block text-sm font-medium text-gray-700 mb-2">
                            Facebook URL
                        </label>
                        <input type="url" 
                               id="social_links.facebook"
                               wire:model="social_links.facebook" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('social_links.facebook') border-red-500 @enderror"
                               placeholder="https://facebook.com/yourpage">
                        @error('social_links.facebook')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label for="social_links.instagram" class="block text-sm font-medium text-gray-700 mb-2">
                            Instagram URL
                        </label>
                        <input type="url" 
                               id="social_links.instagram"
                               wire:model="social_links.instagram" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('social_links.instagram') border-red-500 @enderror"
                               placeholder="https://instagram.com/yourpage">
                        @error('social_links.instagram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Twitter -->
                    <div>
                        <label for="social_links.twitter" class="block text-sm font-medium text-gray-700 mb-2">
                            Twitter URL
                        </label>
                        <input type="url" 
                               id="social_links.twitter"
                               wire:model="social_links.twitter" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('social_links.twitter') border-red-500 @enderror"
                               placeholder="https://twitter.com/yourpage">
                        @error('social_links.twitter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- YouTube -->
                    <div>
                        <label for="social_links.youtube" class="block text-sm font-medium text-gray-700 mb-2">
                            YouTube URL
                        </label>
                        <input type="url" 
                               id="social_links.youtube"
                               wire:model="social_links.youtube" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('social_links.youtube') border-red-500 @enderror"
                               placeholder="https://youtube.com/yourchannel">
                        @error('social_links.youtube')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium flex items-center">
                    <i data-feather="save" class="w-4 h-4 mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Feedback Modal -->
    @if($showFeedbackModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeFeedbackModal"></div>

                <!-- Center the modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $feedbackType === 'success' ? 'bg-green-100' : 'bg-red-100' }} sm:mx-0 sm:h-10 sm:w-10">
                                @if($feedbackType === 'success')
                                    <i data-feather="check" class="h-6 w-6 text-green-600"></i>
                                @else
                                    <i data-feather="x" class="h-6 w-6 text-red-600"></i>
                                @endif
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $feedbackType === 'success' ? 'Success!' : 'Error!' }}
                                </h3>
                                <div class="mt-2">
                                    <div class="text-sm text-gray-500 whitespace-pre-line">
                                        {{ $feedbackMessage }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                wire:click="closeFeedbackModal"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
