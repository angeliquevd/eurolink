<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('spaces.show', $space) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to {{ $space->name }}
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <flux:heading size="xl" class="mb-2">Platform Registration</flux:heading>
            <flux:subheading>Register your platform with the European Commission</flux:subheading>
        </div>

        <form wire:submit="submit">
            <!-- Company Information -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Company Information</flux:heading>

                <div class="space-y-4">
                    <flux:input
                        wire:model="company_name"
                        label="Company Name"
                        placeholder="Your company legal name"
                        required
                    />

                    <flux:input
                        wire:model="company_registration_number"
                        label="Company Registration Number"
                        placeholder="e.g., EU VAT number"
                    />

                    <flux:input
                        wire:model="company_country"
                        label="Country"
                        placeholder="Country where company is registered"
                        required
                    />

                    <flux:textarea
                        wire:model="company_address"
                        label="Company Address"
                        placeholder="Full registered address"
                        rows="3"
                        required
                    />

                    <flux:input
                        wire:model="company_website"
                        type="url"
                        label="Company Website"
                        placeholder="https://example.com"
                    />
                </div>
            </flux:card>

            <!-- Contact Information -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Contact Person</flux:heading>

                <div class="space-y-4">
                    <flux:input
                        wire:model="contact_person_name"
                        label="Full Name"
                        required
                    />

                    <flux:input
                        wire:model="contact_person_title"
                        label="Job Title"
                        placeholder="e.g., Chief Compliance Officer"
                        required
                    />

                    <flux:input
                        wire:model="contact_person_email"
                        type="email"
                        label="Email Address"
                        required
                    />

                    <flux:input
                        wire:model="contact_person_phone"
                        type="tel"
                        label="Phone Number"
                        placeholder="+32 2 123 4567"
                    />
                </div>
            </flux:card>

            <!-- Platform Services Information -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Platform Services Information</flux:heading>

                <div class="space-y-4">
                    <flux:textarea
                        wire:model="ai_systems_description"
                        label="Platform Services Description"
                        placeholder="Describe the services your platform provides..."
                        rows="4"
                        required
                    />

                    <flux:checkbox.group wire:model="ai_system_types" label="Service Types (select all that apply)" class="[&_[data-flux-checkbox][data-checked]_svg]:!text-zinc-900 dark:[&_[data-flux-checkbox][data-checked]_svg]:!text-white">
                        <flux:checkbox value="general_purpose" label="General Purpose Services" />
                        <flux:checkbox value="high_risk" label="High-Risk Services" />
                        <flux:checkbox value="limited_risk" label="Limited Risk Services" />
                        <flux:checkbox value="minimal_risk" label="Minimal Risk Services" />
                    </flux:checkbox.group>

                    <flux:textarea
                        wire:model="intended_use_cases"
                        label="Intended Use Cases"
                        placeholder="Describe the primary use cases and applications of your platform services..."
                        rows="4"
                    />
                </div>
            </flux:card>

            <!-- Additional Information -->
            <flux:card class="mb-6">
                <flux:heading size="lg" class="mb-4">Additional Information</flux:heading>

                <flux:textarea
                    wire:model="additional_notes"
                    label="Additional Notes (Optional)"
                    placeholder="Any additional information you would like to provide..."
                    rows="4"
                />
            </flux:card>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <flux:button href="{{ route('spaces.show', $space) }}" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Submit Registration
                </flux:button>
            </div>
        </form>
    </div>
</div>
