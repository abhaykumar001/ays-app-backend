<?php

return [

    // Dashboard
    [
        'title' => 'Dashboard',
        'icon' => 'bi bi-house-fill',
        'route' => 'dashboard',
        'permission' => 'view_dashboard',
    ],

    // Projects
    [
        'title' => 'Projects',
        'icon' => 'bi bi-building',
        'permissions' => [
            'view_projects',
            'create_projects',
            'view_phases',
            'view_amenities',
            'view_highlights',
            'view_nearby_places',
            'view_virtual_tours',
            'view_payment_plans',
            'view_communities',
            'view_accommodations'
        ],
        'children' => [
            ['title' => 'All Projects', 'route' => 'projects.index', 'icon' => 'bi bi-list', 'permission' => 'view_projects'],
            ['title' => 'Add Project', 'route' => 'projects.create', 'icon' => 'bi bi-plus', 'permission' => 'create_projects'],
            ['title' => 'Amenities', 'route' => 'amenities.index', 'icon' => 'bi bi-activity', 'permission' => 'view_amenities'],
            // ['title' => 'Highlights', 'route' => 'highlights.index', 'icon' => 'bi bi-star', 'permission' => 'view_highlights'],
            // ['title' => 'Nearby Places', 'route' => 'nearbyPlaces.index', 'icon' => 'bi bi-geo-alt', 'permission' => 'view_nearby_places'],
            // ['title' => 'Virtual Tours / 3D Links', 'route' => 'virtualTours.index', 'icon' => 'bi bi-camera-reels', 'permission' => 'view_virtual_tours'],
            // // Payment Plans
            // [ 'title' => 'Payment Plans', 'icon' => 'bi bi-credit-card-2-front', 'route' => 'paymentPlans.index', 'permission' => 'view_payment_plans' ],
            [ 'title' => 'Community/ Master Plan', 'icon' => 'bi bi-buildings', 'route' => 'communities.index', 'permission' => 'view_communities' ],
            [ 'title' => 'Accommodation', 'icon' => 'bi bi-alt', 'route' => 'accommodations.index', 'permission' => 'view_accommodations' ],
            [ 'title' => 'Construction Stages', 'icon' => 'bi bi-bar-chart-steps', 'route' => 'constructionStages.index', 'permission' => 'view_construction_updates' ],
        ],
    ],

    // // Units
    // [
    //     'title' => 'Units',
    //     'icon' => 'bi bi-building-check',
    //     'permissions' => ['view_units', 'create_units', 'view_unit_prices', 'view_unit_media', 'view_availability'],
    //     'children' => [
    //         // ['title' => 'All Units', 'route' => 'units.index', 'icon' => 'bi bi-list', 'permission' => 'view_units'],
    //         // ['title' => 'Add Unit', 'route' => 'units.create', 'icon' => 'bi bi-plus', 'permission' => 'create_units'],
    //         // ['title' => 'Unit Prices', 'route' => 'unitPrices.index', 'icon' => 'bi bi-currency-dollar', 'permission' => 'view_unit_prices'],
    //         // ['title' => 'Unit Media', 'route' => 'unitMedia.index', 'icon' => 'bi bi-images', 'permission' => 'view_unit_media'],
    //         // ['title' => 'Availability Control', 'route' => 'unitAvailability.index', 'icon' => 'bi bi-calendar-check', 'permission' => 'view_availability'],
    //         // ['title' => 'Booking Activity Timeline', 'route' => 'units.timeline', 'icon' => 'bi bi-clock-history', 'permission' => 'view_unit_timeline'],
    //     ],
    // ],

    // Bookings
    // [
    //     'title' => 'Bookings',
    //     'icon' => 'bi bi-journal-bookmark',
    //     'permissions' => ['view_reservations', 'view_payments', 'view_cancellations'],
    //     'children' => [
    //         ['title' => 'Reservations', 'route' => 'reservations.index', 'icon' => 'bi bi-card-checklist', 'permission' => 'view_reservations'],
    //         ['title' => 'Payments', 'route' => 'bookings.payments', 'icon' => 'bi bi-wallet', 'permission' => 'view_payments'],
    //         ['title' => 'Cancel / Release Units', 'route' => 'cancellations.index', 'icon' => 'bi bi-x-circle', 'permission' => 'view_cancellations'],
    //     ],
    // ],

    // Events
    [
        'title' => 'Events',
        'icon' => 'bi bi-calendar-event',
        'permissions' => ['request_event', 'view_events', 'view_event_registrations'],
        'children' => [
            ['title' => 'Request to Host Event', 'route' => 'requests.index', 'icon' => 'bi bi-pencil-square', 'permission' => 'request_event'],
            ['title' => 'All Events', 'route' => 'events.index', 'icon' => 'bi bi-calendar-check', 'permission' => 'view_events'],
            ['title' => 'Event Registrations', 'route' => 'registrations.index', 'icon' => 'bi bi-people', 'permission' => 'view_event_registrations'],
        ],
    ],
    // Leads / Enquiries
    [
        'title' => 'Leads',
        'icon' => 'bi bi-person-lines-fill',
        'permissions' => ['view_enquiries', 'view_viewings'],
        'children' => [
            ['title' => 'Enquiries', 'route' => 'enquiries.index', 'icon' => 'bi bi-list', 'permission' => 'view_enquiries'],
            ['title' => 'Viewings', 'route' => 'viewings.index', 'icon' => 'bi bi-eye', 'permission' => 'view_viewings'],
        ],
    ],

    // Agents
    // [
    //     'title' => 'Agents',
    //     'icon' => 'bi bi-person-bounding-box',
    //     'route' => 'agents.index',
    //     'permission' => 'view_agents',
    // ],
    // Buyers
    // [
    //     'title' => 'Buyers',
    //     'icon' => 'bi bi-people-fill',
    //     'route' => 'buyers.index',
    //     'permission' => 'view_buyers',
    // ],
    // Construction Updates
    // [
    //     'title' => 'Construction Updates',
    //     'icon' => 'bi bi-building-gear',
    //     'route' => 'constructionUpdates.index',
    //     'permission' => 'view_construction_updates',
    // ],

    // Content Management
    [
        'title' => 'Content Management',
        'icon' => 'bi bi-substack',
        'permissions' => ['view_blogs', 'view_tags', 'view_market_insights', 'view_announcements', 'view_offers'],
        'children' => [
            ['title' => 'AYS News', 'route' => 'news.index', 'icon' => 'bi bi-newspaper', 'permission' => 'view_news'],
            ['title' => 'AYS Kiosk Slides', 'route' => 'kioskSlides.index', 'icon' => 'bi bi-images', 'permission' => 'view_kiosk_slides'],
            ['title' => 'Team Members', 'route' => 'teamMembers.index', 'icon' => 'bi bi-people', 'permission' => 'view_team_members'],
            ['title' => 'Team Categories', 'route' => 'teamMemberCategories.index', 'icon' => 'bi bi-diagram-3', 'permission' => 'view_team_member_categories'],
            ['title' => 'Tags', 'route' => 'tags.index', 'icon' => 'bi bi-tags', 'permission' => 'view_tags'],
            ['title' => 'Blogs', 'route' => 'blogs.index', 'icon' => 'bi bi-journal-text', 'permission' => 'view_blogs'],
            ['title' => 'Market Insights', 'route' => 'marketInsights.index', 'icon' => 'bi bi-newspaper', 'permission' => 'view_market_insights'],
            ['title' => 'Offers', 'route' => 'offers.index', 'icon' => 'bi bi-broadcast', 'permission' => 'view_offers'],
            ['title' => 'Design Philosophy', 'route' => 'design-philosophy.edit', 'icon' => 'bi bi-brush', 'permission' => 'view_design_philosophy'],
        ],
    ],
    // Notifications / Alerts (push, role-targeted, optionally scheduled)
    [
        'title' => 'Notifications',
        'icon' => 'bi bi-bell-fill',
        'permissions' => ['view_notifications', 'create_notifications'],
        'children' => [
            [
                'title' => 'All Notifications',
                'route' => 'notifications.index',
                'icon' => 'bi bi-bell',
                'permission' => 'view_notifications',
            ],
            [
                'title' => 'Create Notification',
                'route' => 'notifications.create',
                'icon' => 'bi bi-bell-plus',
                'permission' => 'create_notifications',
            ],
        ],
    ],
    // Property Management
    // [
    //     'title' => 'Property Management',
    //     'icon' => 'bi bi-kanban',
    //     'permissions' => ['view_maintanance', 'view_owners', 'view_payments', 'view_payment_schedules', 'view_maintanance_requests'],
    //     'children' => [
    //         ['title' => 'Maintanance', 'route' => 'maintanance.index', 'icon' => 'bi bi-house-gear-fill', 'permission' => 'view_maintanance'],
    //         ['title' => 'Owners', 'route' => 'owners.index', 'icon' => 'bi bi-person-rolodex', 'permission' => 'view_owners'],
    //         ['title' => 'Payments', 'route' => 'payments.index', 'icon' => 'bi bi-wallet', 'permission' => 'view_payments'],
    //         ['title' => 'Payment Schedules', 'route' => 'paymentSchedules.index', 'icon' => 'bi bi-credit-card', 'permission' => 'view_payment_schedules'],
    //         ['title' => 'Maintanance Requests', 'route' => 'maintananceRequests.index', 'icon' => 'bi bi-person-fill-gear', 'permission' => 'view_maintanance_requests'],
    //     ],
    // ],

    [
        'title' => 'Users & Roles',
        'icon' => 'bi bi-people-fill',
        'permissions' => ['view_user', 'view_roles', 'view_permission'],
        'children' => [
            [
                'title' => 'All Users',
                'route' => 'user.index',
                'icon' => 'bi bi-person-plus',
                'permission' => 'view_user',
            ],
            [
                'title' => 'Roles',
                'route' => 'roles.index',
                'icon' => 'bi bi-person-bounding-box',
                'permission' => 'view_roles',
            ],
            [
                'title' => 'Permissions',
                'route' => 'permission.index',
                'icon' => 'bi bi-person-fill-lock',
                'permission' => 'view_permission',
            ],
        ],
    ],

    // Audit Logs
    // [
    //     'title' => 'Audit Logs',
    //     'icon' => 'bi bi-clock-history',
    //     'route' => 'auditLogs.index',
    //     'permission' => 'view_audit_logs',
    // ],

    // SEO Meta Data
    // [
    //     'title' => 'SEO Meta Data',
    //     'icon' => 'bi bi-activity',
    //     'route' => 'seoData.index',
    //     'permission' => 'view_seo_data',
    // ],

    // Profile
    [
        'title' => 'Profile',
        'icon' => 'bi bi-person-badge-fill',
        'route' => 'profile.edit',
    ],

    // Settings
    [
        'title' => 'Settings',
        'icon' => 'bi bi-gear',
        'route' => 'website.settings',
        'permission' => 'view_website_settings',
    ],

];
