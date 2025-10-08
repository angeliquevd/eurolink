<div>
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <flux:heading size="lg" class="font-bold text-blue-600 dark:text-blue-400">
                        Eurolink
                    </flux:heading>
                    <div class="hidden md:flex gap-6">
                        <a href="#features" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Features</a>
                        <a href="#how-it-works" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">How it works</a>
                        <a href="#security" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">Security</a>
                        <a href="#faq" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">FAQ</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <flux:button href="{{ route('dashboard') }}" variant="ghost">Dashboard</flux:button>
                    @else
                        <flux:button href="{{ route('login') }}" variant="ghost">Sign in</flux:button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if (session()->has('message'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-green-700 dark:text-green-300">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 py-20 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <flux:heading size="xl" class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    A modern communication hub between the European Commission and its stakeholders
                </flux:heading>
                <flux:subheading class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    Eurolink centralises spaces, threads, and announcements—so projects move faster and stay compliant.
                </flux:subheading>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <flux:button wire:click="openRequestAccess" variant="primary">
                        Request Access
                    </flux:button>
                    <flux:button href="mailto:eurolink@ec.europa.eu" variant="ghost">
                        Book a Demo
                    </flux:button>
                </div>
                <flux:text class="text-sm text-gray-500 dark:text-gray-400">
                    Built with EU-grade security • PostgreSQL • Audit trails • Hosting in the EU
                </flux:text>
            </div>
        </div>
    </section>

    <!-- Value Propositions -->
    <section class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Structured collaboration</flux:heading>
                    <flux:text>Spaces, threads, and mentions keep everyone aligned.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Targeted announcements</flux:heading>
                    <flux:text>Broadcast to the right audience, with delivery receipts.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Personal inbox</flux:heading>
                    <flux:text>See replies, mentions, and updates in one place.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Powerful search</flux:heading>
                    <flux:text>Find threads, users, and announcements instantly.</flux:text>
                </flux:card>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">How it works</flux:heading>
                <flux:subheading>Three simple steps to better collaboration</flux:subheading>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">1</flux:heading>
                    </div>
                    <flux:heading size="lg" class="mb-3">Join or create a Space</flux:heading>
                    <flux:text>Organize by topic or project with public or private visibility.</flux:text>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">2</flux:heading>
                    </div>
                    <flux:heading size="lg" class="mb-3">Discuss & share</flux:heading>
                    <flux:text>Create threads, mention colleagues, and attach documents.</flux:text>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">3</flux:heading>
                    </div>
                    <flux:heading size="lg" class="mb-3">Publish announcements</flux:heading>
                    <flux:text>Broadcast updates with permissions and full audit logging.</flux:text>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">Features built for the EC</flux:heading>
                <flux:subheading>Everything you need for secure, compliant collaboration</flux:subheading>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Spaces</flux:heading>
                    <flux:text class="mb-4">Public or private discussion areas organized by project, topic, or team. Control who sees what with granular permissions.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Threads & Posts</flux:heading>
                    <flux:text class="mb-4">Structured conversations that keep context intact. Reply, mention, and collaborate without losing track.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Announcements</flux:heading>
                    <flux:text class="mb-4">Broadcast important updates to space members. Track who has seen your message with delivery receipts.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Personal Inbox</flux:heading>
                    <flux:text class="mb-4">Never miss a mention, reply, or announcement. Your personalized feed keeps you up to date.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Global Search</flux:heading>
                    <flux:text class="mb-4">Find threads, posts, users, and announcements instantly. Filter by space, date, or author.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Role-based Access</flux:heading>
                    <flux:text class="mb-4">Provider, EC Staff, and Observer roles with customizable permissions per space.</flux:text>
                </flux:card>
            </div>
        </div>
    </section>

    <!-- Security & Compliance -->
    <section id="security" class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">Security & Compliance</flux:heading>
                <flux:subheading>Built to EU standards from day one</flux:subheading>
            </div>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <flux:card>
                    <flux:heading size="lg" class="mb-2">EU-grade security</flux:heading>
                    <flux:text>Role-based access control, encryption in transit and at rest, full audit logging.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-2">Data sovereignty</flux:heading>
                    <flux:text>All data hosted within the EU on compliant infrastructure.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-2">Audit trails</flux:heading>
                    <flux:text>Complete history of who did what, when—for compliance and transparency.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-2">On the roadmap</flux:heading>
                    <flux:text>EU Login/OIDC integration, SSO, advanced data retention policies.</flux:text>
                </flux:card>
            </div>
        </div>
    </section>

    <!-- Use Cases -->
    <section class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">Use cases</flux:heading>
                <flux:subheading>See how different teams use Eurolink</flux:subheading>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Providers → EC teams</flux:heading>
                    <flux:text>Submit information, get feedback, and stay updated on regulatory developments.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Inter-DG coordination</flux:heading>
                    <flux:text>Shared spaces for cross-unit initiatives, policy development, and project management.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-3">Public-facing updates</flux:heading>
                    <flux:text>Read-only spaces for observers to follow developments and announcements.</flux:text>
                </flux:card>
            </div>
        </div>
    </section>

    <!-- Integrations -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">Integrations</flux:heading>
                <flux:subheading>Connect with your existing tools</flux:subheading>
            </div>
            <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <flux:card>
                    <flux:heading size="lg" class="mb-2">Email notifications</flux:heading>
                    <flux:text>Stay informed with digest emails and instant alerts.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-2">EU Login (Soon)</flux:heading>
                    <flux:text>OIDC and SSO integration on the roadmap.</flux:text>
                </flux:card>
                <flux:card>
                    <flux:heading size="lg" class="mb-2">API Access</flux:heading>
                    <flux:text>Read-only API for integrations and data export.</flux:text>
                </flux:card>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <flux:heading size="xl" class="mb-4">Frequently Asked Questions</flux:heading>
            </div>
            <flux:accordion>
                <flux:accordion.item>
                    <flux:accordion.heading>Who can request access?</flux:accordion.heading>
                    <flux:accordion.content>
                        Any stakeholder working with the European Commission, including AI providers, EC staff members, and authorized observers. Access is granted based on your role and need.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>How are spaces moderated?</flux:accordion.heading>
                    <flux:accordion.content>
                        Each space has designated owners and moderators with EC Staff permissions. They can manage members, moderate discussions, and publish announcements.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>Where is data hosted?</flux:accordion.heading>
                    <flux:accordion.content>
                        All data is hosted within the European Union on compliant infrastructure, meeting EU data sovereignty requirements.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>Can I export my data?</flux:accordion.heading>
                    <flux:accordion.content>
                        Yes, authorized users can export their data through the API or request a data export from administrators.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>Is EU Login supported?</flux:accordion.heading>
                    <flux:accordion.content>
                        EU Login/OIDC integration is on our roadmap and will be available in a future release. Currently, we use email-based authentication.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>What about multilingual content?</flux:accordion.heading>
                    <flux:accordion.content>
                        English and French are supported at launch. Additional EU languages will be added based on user needs.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>How does search work?</flux:accordion.heading>
                    <flux:accordion.content>
                        Global search allows you to find threads, posts, users, and announcements across all spaces you have access to. You can filter by space, date range, and author.
                    </flux:accordion.content>
                </flux:accordion.item>

                <flux:accordion.item>
                    <flux:accordion.heading>What are the different user roles?</flux:accordion.heading>
                    <flux:accordion.content>
                        Provider: External stakeholders who can participate in discussions. EC Staff: European Commission employees with moderation and announcement privileges. Observer: Read-only access for monitoring and research.
                    </flux:accordion.content>
                </flux:accordion.item>
            </flux:accordion>
        </div>
    </section>

    <!-- CTA Strip -->
    <section class="py-16 bg-blue-600 dark:bg-blue-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <flux:heading size="xl" class="text-white mb-4">
                Ready to get started?
            </flux:heading>
            <flux:text class="text-blue-100 mb-8 text-lg">
                Request access today and join the conversation.
            </flux:text>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <flux:button wire:click="openRequestAccess" variant="primary" class="bg-white text-blue-600 hover:bg-gray-100">
                    Request Access
                </flux:button>
                <flux:button href="mailto:eurolink@ec.europa.eu" variant="ghost" class="text-white border-white hover:bg-blue-700">
                    Book a Demo
                </flux:button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <flux:heading size="lg" class="text-white mb-4">Eurolink</flux:heading>
                    <flux:text class="text-sm">Modern communication for the European Commission.</flux:text>
                </div>
                <div>
                    <flux:heading size="sm" class="text-white mb-4">Contact</flux:heading>
                    <flux:text class="text-sm">
                        <a href="mailto:eurolink@ec.europa.eu" class="hover:text-white">eurolink@ec.europa.eu</a>
                    </flux:text>
                </div>
                <div>
                    <flux:heading size="sm" class="text-white mb-4">Legal</flux:heading>
                    <div class="flex flex-col gap-2 text-sm">
                        <a href="#" class="hover:text-white">Privacy Policy</a>
                        <a href="#" class="hover:text-white">Terms of Service</a>
                        <a href="#" class="hover:text-white">Cookie Policy</a>
                    </div>
                </div>
                <div>
                    <flux:heading size="sm" class="text-white mb-4">Accessibility</flux:heading>
                    <flux:text class="text-sm">
                        WCAG 2.1 AA compliant
                    </flux:text>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm">
                <flux:text>© {{ date('Y') }} European Commission. All rights reserved.</flux:text>
            </div>
        </div>
    </footer>

    <!-- Request Access Modal -->
    <flux:modal name="request-access" :open="$showRequestAccess" wire:model="showRequestAccess">
        <form wire:submit="submitAccessRequest">
            <flux:heading size="lg" class="mb-4">Request Access</flux:heading>
            <flux:text class="mb-6 text-gray-600 dark:text-gray-400">
                Fill out this form and we'll review your request within 2 business days.
            </flux:text>

            <div class="space-y-4">
                <flux:input
                    wire:model="name"
                    label="Full Name"
                    placeholder="Your name"
                    required
                />

                <flux:input
                    wire:model="organization"
                    label="Organization"
                    placeholder="Your organization"
                    required
                />

                <flux:input
                    wire:model="email"
                    type="email"
                    label="Email"
                    placeholder="your.email@example.com"
                    required
                />

                <flux:select wire:model="role" label="Role" placeholder="Select your role" required>
                    <option value="provider">Provider / Stakeholder</option>
                    <option value="ec_staff">EC Staff</option>
                    <option value="observer">Observer / Researcher</option>
                </flux:select>

                @error('name')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
                @error('organization')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
                @error('email')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
                @error('role')
                    <flux:error>{{ $message }}</flux:error>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <flux:button type="button" wire:click="closeRequestAccess" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Submit Request
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
