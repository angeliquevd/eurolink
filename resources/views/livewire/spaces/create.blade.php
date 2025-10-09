<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('spaces.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Spaces
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <flux:heading size="xl" class="mb-2">Create New Space</flux:heading>
            <flux:subheading>Create a collaborative space for your community</flux:subheading>
        </div>

        <form wire:submit="submit">
            <!-- Basic Information -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Basic Information</flux:heading>

                <div class="space-y-4">
                    <flux:input
                        wire:model.live="name"
                        label="Space Name"
                        placeholder="e.g., Digital Innovation Hub"
                        required
                    />

                    <flux:input
                        wire:model="slug"
                        label="URL Slug"
                        placeholder="digital-innovation-hub"
                        description="This will be used in the space's URL. It's auto-generated from the name but you can customize it."
                        required
                    />

                    <flux:textarea
                        wire:model="description"
                        label="Description"
                        placeholder="Describe the purpose and goals of this space..."
                        rows="4"
                    />
                </div>
            </flux:card>

            <!-- Settings -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Settings</flux:heading>

                <div class="space-y-4">
                    <flux:radio.group wire:model="visibility" label="Visibility">
                        <flux:radio value="public" label="Public" description="Anyone can view and join this space" />
                        <flux:radio value="private" label="Private" description="Only invited members can view and participate" />
                    </flux:radio.group>

                    <flux:checkbox
                        wire:model="enable_provider_registration"
                        label="Enable Provider Registration"
                        description="Allow users to register as providers in this space (useful for regulatory compliance spaces)"
                    />
                </div>
            </flux:card>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <flux:button href="{{ route('spaces.index') }}" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Create Space
                </flux:button>
            </div>
        </form>
    </div>
</div>
