<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('teamMembers.index')">Team Members</x-nav-link>
        <span class="mx-2 text-gray-400">/</span>
        <x-nav-link :href="route('teamMembers.create')" :active="true">Add Team Member</x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Add Team Member</h2>

                <form method="POST" action="{{ route('teamMembers.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-12 gap-5">

                        <div class="md:col-span-6">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="team_member_category_id" :value="__('Category')" />
                            <select id="team_member_category_id" name="team_member_category_id"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm w-full">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('team_member_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Type a new name and press Enter to create a category on the fly.</p>
                            <x-input-error :messages="$errors->get('team_member_category_id')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                                :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="languages" :value="__('Languages (comma separated)')" />
                            <x-text-input id="languages" name="languages" type="text" class="mt-1 block w-full"
                                placeholder="English, Hindi" :value="old('languages')" />
                            <x-input-error :messages="$errors->get('languages')" class="mt-2" />
                        </div>

                        <div class="md:col-span-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-textarea id="description" name="description" rows="4" class="mt-1 block w-full">{{ old('description') }}</x-text-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="display_order" :value="__('Display Order')" />
                            <x-text-input id="display_order" name="display_order" type="number" min="0"
                                class="mt-1 block w-full" :value="old('display_order', 0)" />
                            <x-input-error :messages="$errors->get('display_order')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="is_active" :value="__('Status')" />
                            <x-select name="is_active" :options="['1' => 'Active (visible in app)', '0' => 'Inactive (hidden)']"
                                :value="old('is_active', '1')" />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="md:col-span-4">
                            <x-input-label for="image" :value="__('Photo')" />
                            <x-text-input id="image" name="image" type="file" accept="image/*"
                                class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Save Team Member</x-primary-button>
                        <a href="{{ route('teamMembers.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ts = new TomSelect('#team_member_category_id', {
                create: true,
                persist: false,
                maxItems: 1,
                onOptionAdd: function(value, data) {
                    fetch('{{ route('teamMemberCategory.addNew') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name: value })
                        })
                        .then(res => res.json())
                        .then(data => {
                            const newCategory = data.newCategory;
                            const allCategories = data.allCategories;
                            ts.clearOptions();
                            allCategories.forEach(category => {
                                ts.addOption({ value: category.id, text: category.name });
                            });
                            ts.setValue(String(newCategory.id));
                        })
                        .catch(err => console.error(err));
                },
            });
        });
    </script>
</x-app-layout>
