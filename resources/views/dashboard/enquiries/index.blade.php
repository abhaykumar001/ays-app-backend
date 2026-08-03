<x-app-layout>
    <x-slot name="header">
        <x-nav-link :href="route('enquiries.index')" :active="true">
            {{ __('Enquiries') }}
        </x-nav-link>
    </x-slot>

    <div class="py-6">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <div class="flex justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Enquiries
                    </h2>
                </div>

                @php
                    $actions = [];

                    if (auth()->user()->can('edit_enquiries')) {
                        $actions[] = [
                            'type' => 'edit',
                            'label' => 'Edit',
                            'url' => 'enquiries.edit',
                        ];
                    }

                    if (auth()->user()->can('delete_enquiries')) {
                        $actions[] = [
                            'type' => 'delete',
                            'url' => 'enquiries.destroy',
                            'label' => 'Delete',
                        ];
                    }

                    $columns = collect([
                        ['label' => '#'],
                        ['label' => 'Name', 'key' => 'contact_name'],
                        ['label' => 'Email', 'key' => 'contact_email'],
                        ['label' => 'Phone', 'key' => 'contact_phone'],
                        ['label' => 'Project', 'key' => 'project.name'],
                        ['label' => 'Unit', 'key' => 'unit.title'],
                        ['label' => 'Team Member', 'key' => 'teamMember.name'],
                        ['label' => 'Type', 'key' => 'enquiry_type'],
                        ['label' => 'Message', 'key' => 'message'],
                        [
                            'label' => 'Status',
                            'key' => 'status',
                            'badge' => true,
                            'badgeMap' => [
                                'new' => ['text' => 'New', 'color' => 'bg-yellow-500 text-white'],
                                'contacted' => ['text' => 'Contacted', 'color' => 'bg-blue-600 text-white'],
                                'converted' => ['text' => 'Converted', 'color' => 'bg-green-600 text-white'],
                            ],
                        ],
                        ['label' => 'Date', 'key' => 'created_at'],
                        count($actions) ? ['label' => 'Actions', 'actions' => $actions] : null,
                    ])
                                ->filter()
                                ->values()
                                ->toArray();
                @endphp

                <x-datatable :data="$enquiries" :columns="$columns" />
            </div>
        </div>
    </div>
</x-app-layout>
