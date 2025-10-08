<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create main user account
        $mainUser = User::factory()->ecStaff()->withPersonalTeam()->create([
            'name' => 'EC Official',
            'email' => 'ec-official@test.com',
        ]);

        // Create test accounts
        $ecAdmin = User::factory()->ecStaff()->withPersonalTeam()->create([
            'name' => 'EC Admin',
            'email' => 'ec.admin@example.com',
        ]);

        $provider = User::factory()->provider()->withPersonalTeam()->create([
            'name' => 'Alice Provider',
            'email' => 'alice.provider@example.com',
        ]);

        // Create AI provider test account
        $aiProvider = User::factory()->provider()->withPersonalTeam()->create([
            'name' => 'AI Systems Provider',
            'email' => 'provider@test.com',
        ]);

        // Create regular users (26 total, for 30 total with main user + 3 test accounts)
        $users = User::factory(26)->withPersonalTeam()->create();
        $allUsers = collect([$mainUser, $ecAdmin, $provider, $aiProvider])->merge($users);

        // Create AI Office space with provider registration enabled
        $aiOffice = \App\Models\Space::factory()->create([
            'name' => 'AI Office',
            'slug' => 'ai-office',
            'visibility' => 'public',
            'description' => 'European Commission AI Office - Register as an AI provider and collaborate on AI regulation',
            'enable_provider_registration' => true,
        ]);

        // Create other spaces (2 public, 2 private)
        $otherSpaces = collect([
            \App\Models\Space::factory()->create(['visibility' => 'public']),
            \App\Models\Space::factory()->create(['visibility' => 'public']),
            \App\Models\Space::factory()->create(['visibility' => 'private']),
            \App\Models\Space::factory()->create(['visibility' => 'private']),
        ]);

        // Add members to AI Office
        \App\Models\SpaceMembership::create([
            'user_id' => $mainUser->id,
            'space_id' => $aiOffice->id,
            'role_in_space' => 'owner',
        ]);

        \App\Models\SpaceMembership::create([
            'user_id' => $ecAdmin->id,
            'space_id' => $aiOffice->id,
            'role_in_space' => 'moderator',
        ]);

        \App\Models\SpaceMembership::create([
            'user_id' => $provider->id,
            'space_id' => $aiOffice->id,
            'role_in_space' => 'member',
        ]);

        \App\Models\SpaceMembership::create([
            'user_id' => $aiProvider->id,
            'space_id' => $aiOffice->id,
            'role_in_space' => 'member',
        ]);

        // Add 10 more members to AI Office
        $aiOfficeMembers = $allUsers->filter(fn($u) => !in_array($u->id, [$mainUser->id, $ecAdmin->id, $provider->id, $aiProvider->id]))
            ->random(10);

        $aiOfficeMembers->each(function ($user) use ($aiOffice) {
            \App\Models\SpaceMembership::create([
                'user_id' => $user->id,
                'space_id' => $aiOffice->id,
                'role_in_space' => 'member',
            ]);
        });

        $allAiOfficeMembers = collect([$mainUser, $ecAdmin, $provider, $aiProvider])->merge($aiOfficeMembers);

        // Create realistic AI Office threads and discussions
        $this->seedAiOfficeContent($aiOffice, $allAiOfficeMembers, $ecAdmin, $provider, $aiProvider);

        // Create announcement for AI Office
        \App\Models\Announcement::factory()->create([
            'space_id' => $aiOffice->id,
            'created_by' => $ecAdmin->id,
            'published_at' => now()->subDays(7),
            'title' => 'Welcome to the AI Office',
            'body' => "Welcome to the European Commission's AI Office space on Eurolink.\n\nThis space is dedicated to fostering collaboration between the European Commission and AI providers. Here you can:\n\n- Register your company as an AI provider\n- Participate in discussions about AI regulation and compliance\n- Stay updated on the latest developments in the EU AI Act\n- Connect with other AI providers and EC staff\n\nWe encourage all AI providers to register and participate actively in discussions. Your input is valuable as we work together to build a safe and trustworthy AI ecosystem in Europe.\n\nFor questions or support, please reach out to our team.",
        ]);

        // For other spaces, create generic content
        $otherSpaces->each(function ($space, $index) use ($allUsers, $ecAdmin, $mainUser) {
            \App\Models\SpaceMembership::create([
                'user_id' => $mainUser->id,
                'space_id' => $space->id,
                'role_in_space' => 'moderator',
            ]);

            $memberCount = $space->isPublic() ? rand(10, 20) : rand(5, 10);
            $members = $allUsers->filter(fn($u) => $u->id !== $mainUser->id)->random($memberCount);

            $members->each(function ($user, $idx) use ($space) {
                \App\Models\SpaceMembership::create([
                    'user_id' => $user->id,
                    'space_id' => $space->id,
                    'role_in_space' => $idx === 0 ? 'owner' : ($idx < 3 ? 'moderator' : 'member'),
                ]);
            });

            \App\Models\Thread::factory(8)->create([
                'space_id' => $space->id,
                'created_by' => $members->random()->id,
            ])->each(function ($thread) use ($members) {
                \App\Models\Post::factory(rand(5, 10))->create([
                    'thread_id' => $thread->id,
                    'created_by' => $members->random()->id,
                ]);
            });

            \App\Models\Announcement::factory()->create([
                'space_id' => $space->id,
                'created_by' => $ecAdmin->id,
                'published_at' => now(),
            ]);
        });
    }

    private function seedAiOfficeContent($aiOffice, $members, $ecAdmin, $provider, $aiProvider): void
    {
        // Thread 1: AI Act Implementation Timeline
        $thread1 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'AI Act Implementation Timeline - Questions and Clarifications',
            'created_by' => $provider->id,
            'created_at' => now()->subDays(10),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Hello everyone,\n\nI'm trying to understand the implementation timeline for the EU AI Act. Our company develops AI systems for healthcare diagnostics, which I believe falls under the high-risk category.\n\nCould someone from the EC clarify when we need to have our compliance documentation ready? We want to ensure we're fully prepared before the deadlines.\n\nThanks!",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(10),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Hi Alice,\n\nGreat question! The AI Act implementation follows a phased approach:\n\n1. **6 months after entry into force**: Prohibited AI practices ban\n2. **12 months**: Governance and notification requirements for general-purpose AI\n3. **24 months**: Obligations for high-risk AI systems (this would apply to you)\n4. **36 months**: Full implementation for all remaining provisions\n\nFor high-risk AI systems in healthcare, you'll need to ensure compliance within 24 months of the Act entering into force. I recommend starting your compliance work now to avoid last-minute issues.\n\nWould you like to schedule a consultation to discuss your specific use case?",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(10)->addHours(3),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "That's very helpful, thank you! Yes, I'd appreciate a consultation. We're particularly concerned about the technical documentation requirements and conformity assessments.\n\nShould I reach out via the registration form or is there another process?",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(9),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "We're in a similar situation with our computer vision systems for manufacturing quality control. Would it be possible to organize a group session for high-risk AI providers? I think many of us have similar questions.",
            'created_by' => $aiProvider->id,
            'created_at' => now()->subDays(9)->addHours(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Excellent idea! We're planning a webinar series on high-risk AI compliance. I'll post the details in the announcements section once we have the schedule finalized. It should be within the next 2-3 weeks.\n\nIn the meantime, you can find preliminary guidance documents in our resource library.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(8),
        ]);

        // Thread 2: Risk Classification Questions
        $thread2 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Risk Classification: Is my AI system high-risk?',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(8),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "We've developed an AI-powered chatbot for customer service in e-commerce. It doesn't make any decisions about creditworthiness or access to services - it just answers product questions and helps with order tracking.\n\nI'm trying to determine if this falls under high-risk AI systems or if it's minimal risk. Can anyone provide guidance?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(8),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "Based on your description, this would likely be classified as minimal risk AI. Customer service chatbots that don't make decisions affecting legal rights, access to services, or creditworthiness typically fall into this category.\n\nHowever, you should still follow transparency obligations - users should be informed they're interacting with an AI system.\n\nThe key factors for high-risk classification are:\n- Use in critical infrastructure\n- Educational/vocational training and assessment\n- Employment and worker management\n- Access to essential services\n- Law enforcement\n- Migration and border control\n- Administration of justice\n\nYour use case doesn't appear to fit these categories.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(7),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "That's a relief! So our main obligation is transparency? Do we need to register the system somewhere or just ensure we have proper disclosure in our UI?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(7)->addHours(1),
        ]);

        // Thread 3: Technical Documentation Requirements
        $thread3 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Technical Documentation Requirements - Best Practices',
            'created_by' => $aiProvider->id,
            'created_at' => now()->subDays(6),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread3->id,
            'body' => "For those preparing technical documentation for high-risk AI systems, what format and level of detail is expected?\n\nWe're documenting our training data, model architecture, and validation results, but I want to make sure we're not missing anything critical.\n\nHas anyone gone through this process already? Any templates or examples would be incredibly helpful.",
            'created_by' => $aiProvider->id,
            'created_at' => now()->subDays(6),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread3->id,
            'body' => "The technical documentation should include:\n\n1. **General description** of the AI system and its intended purpose\n2. **Detailed design specifications** including:\n   - General logic of the AI system\n   - Algorithms and techniques used\n   - Key design choices and trade-offs\n3. **Description of system components**\n4. **Data requirements**:\n   - Training datasets (sources, characteristics, preprocessing)\n   - Testing datasets\n   - Data governance measures\n5. **Computational resources used**\n6. **Monitoring, functioning and control** mechanisms\n7. **Validation and testing procedures**\n8. **Risk management measures**\n\nWe're working on publishing templates soon. In the meantime, I recommend checking Annex IV of the AI Act for the complete requirements.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(5),
        ]);

        // Thread 4: General Purpose AI Models
        $thread4 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'General Purpose AI Models - New Transparency Requirements',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(5),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "Our company develops foundation models that can be used for multiple purposes. With the new GPAI provisions, what are the specific transparency requirements we need to meet?\n\nI understand there's a distinction between regular GPAI and those with systemic risk. How is systemic risk determined?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(5),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "General purpose AI models have specific requirements:\n\n**All GPAI models must:**\n- Provide technical documentation\n- Make information and documentation available to downstream providers\n- Put in place a policy to comply with copyright law\n- Publish a summary of training data\n\n**GPAI models with systemic risk** (determined by computing power threshold or Commission designation) must additionally:\n- Conduct model evaluations\n- Assess and mitigate systemic risks\n- Conduct adversarial testing\n- Report serious incidents\n- Ensure adequate level of cybersecurity\n\nCurrently, the threshold is 10^25 FLOPs, but this can be adjusted.\n\nAre you approaching this threshold?",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(4),
        ]);

        // Thread 5: Data Governance and Privacy
        $thread5 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Data Governance and GDPR Alignment',
            'created_by' => $provider->id,
            'created_at' => now()->subDays(4),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread5->id,
            'body' => "How do the AI Act's data governance requirements interact with GDPR?\n\nWe're already GDPR compliant, but I want to understand if there are additional data-related requirements under the AI Act that go beyond what GDPR requires.\n\nSpecifically regarding training data management and data quality requirements.",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(4),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread5->id,
            'body' => "Good question! The AI Act and GDPR are complementary:\n\n**GDPR focuses on:** Personal data protection, consent, rights of data subjects\n\n**AI Act focuses on:** Data quality, representativeness, relevance for the AI system's purpose\n\nKey AI Act data requirements:\n- Training data must be relevant, representative, and free of errors\n- Data management practices for training, validation, testing\n- Examination of biases in datasets\n- Data governance measures throughout the system lifecycle\n\nIf you're using personal data, you need both GDPR compliance AND AI Act data quality requirements. They work together - GDPR protects individuals, AI Act ensures AI system quality and safety.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(3),
        ]);

        // Thread 6: Provider Registration Process
        $thread6 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Provider Registration Process - Questions',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(3),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "I just completed the provider registration form. What happens next? How long does the review process typically take?\n\nAlso, once approved, what ongoing obligations do we have?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(3),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "Registration reviews are typically completed within 5-7 business days. You'll receive an email notification once your registration is processed.\n\nOnce registered, ongoing obligations include:\n- Keeping your registration information up to date\n- Notifying us of significant changes to your AI systems\n- Participating in market surveillance when requested\n- Reporting serious incidents\n- Maintaining compliance documentation\n\nYou'll also gain access to additional resources and consultation opportunities.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(2),
        ]);

        // Thread 7: SME Support and Resources
        $thread7 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Support for Small and Medium Enterprises',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread7->id,
            'body' => "As a small startup (12 employees) developing AI solutions, we're concerned about the compliance costs and administrative burden.\n\nAre there any simplified procedures or support programs for SMEs? We want to be compliant but need guidance on prioritizing our efforts with limited resources.",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread7->id,
            'body' => "Yes! The AI Act includes specific provisions to support SMEs:\n\n1. **Regulatory sandboxes**: Test AI systems in controlled environments\n2. **Priority access to sandboxes** for SMEs and startups\n3. **Reduced fees** for conformity assessment for SMEs\n4. **AI Office support**: Dedicated SME helpdesk\n5. **Templates and guidelines**: Simplified compliance documentation\n\nWe're also organizing SME-specific workshops and providing access to compliance tools at reduced or no cost.\n\nI recommend applying for our regulatory sandbox program - it's designed specifically for innovative SMEs like yours. Application link coming soon!",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(1),
        ]);

        // Thread 8: International Providers
        $thread8 = \App\Models\Thread::create([
            'space_id' => $aiOffice->id,
            'title' => 'Non-EU Providers - Compliance Requirements',
            'created_by' => $members->random()->id,
            'created_at' => now()->subHours(18),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread8->id,
            'body' => "Our company is based in Switzerland but we offer AI services to customers in the EU. Do we need to comply with the AI Act?\n\nIf yes, do we need an authorized representative in the EU?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subHours(18),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread8->id,
            'body' => "Yes, the AI Act has extraterritorial reach. If you're placing AI systems on the EU market or your AI system's output is used in the EU, you must comply.\n\n**For non-EU providers, you must:**\n- Designate an authorized representative in the EU (for providers outside EU)\n- This representative handles compliance, documentation, and communication with authorities\n- Full compliance with all relevant AI Act requirements\n\nThe authorized representative must be established in the EU and appointed in writing. They act as your point of contact with authorities and users.\n\nWould you like information on selecting an authorized representative?",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subHours(12),
        ]);
    }
}
