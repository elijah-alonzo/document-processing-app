This is the architecture guide for this project so you can navigate through it. Its a Domain Driven Design.

```
app/
├── Features/                 
│   ├── Feature/
│   │   ├── Models/
│   │   │   └── Feature.php
│   │   ├── Controllers/
│   │   │   └── FeatureController.php
│   │   ├── Actions/
│   │   │   └── CreateFeature.php
│   │   ├── Livewire/
│   │   │   └── FeatureForm.php
│   │   ├── Policies/
│   │   │   └── FeaturePolicy.php
│   │   ├── Tests/
│   │   │   └── FeatureTest.php
│   │   ├── Views/
│   │   │   └── Feature.blade.php
│   │   ├── Routes/
│   │   │   └── web.php
│   │   └── Providers/
│   │       └── FeatureServiceProvider.php
│   │
│   └── Shared/
│       ├── Traits/
│       └── Helpers/
│
├── Filament/                
│   ├── Resources/
│   │   └── UserResource/
│   │       ├── UserResource.php
│   │       ├── Pages/
│   │       │   ├── ListUsers.php
│   │       │   ├── CreateUser.php
│   │       │   └── EditUser.php
│   │       ├── Relations/
│   │       │   └── UserPostsRelation.php
│   │       ├── Widgets/
│   │       │   └── UserStatsWidget.php
│   │       ├── Schemas/
│   │       │   └── UserForm.php
│   │       └── Tables/
│   │           └── UserTable.php
│   │
│   ├── Pages/
│   │   ├── Dashboard.php
│   │   └── Settings.php
│   │
│   ├── Widgets/
│   │   ├── StatsOverview.php
│   │   └── RecentActivity.php
│   │
│   ├── Hooks/
│   │   └── GlobalHooks.php
│   │
│   └── Clusters/
│       ├── UserManagement/
│       │   ├── UserManagement.php
│       │   ├── Resources/
│       │   │   └── UserResource.php
│       │   └── Pages/
│       │       └── ManageUsers.php
│       └── ContentManagement/
│           ├── ContentManagement.php
│           ├── Resources/
│           │   └── PostResource.php
│           └── Pages/
│               └── ManageContent.php
│
└── Providers/
    ├── AppServiceProvider.php
    ├── AuthServiceProvider.php
    ├── EventServiceProvider.php
    └── Filament/
        ├── AdminPanelProvider.php
        └── UserPanelProvider.php
```

This structure keeps everything organized and modular, making it easy to find and manage different parts of the application.