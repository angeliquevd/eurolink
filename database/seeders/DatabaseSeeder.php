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

        // Create DSA Office space with provider registration enabled
        $dsaOffice = \App\Models\Space::factory()->create([
            'name' => 'DSA Office',
            'slug' => 'dsa-office',
            'visibility' => 'public',
            'description' => 'Digital Services Act Office - Platform registration, content moderation, and digital services compliance',
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

        // Add members to DSA Office
        \App\Models\SpaceMembership::create([
            'user_id' => $mainUser->id,
            'space_id' => $dsaOffice->id,
            'role_in_space' => 'owner',
        ]);

        \App\Models\SpaceMembership::create([
            'user_id' => $ecAdmin->id,
            'space_id' => $dsaOffice->id,
            'role_in_space' => 'moderator',
        ]);

        \App\Models\SpaceMembership::create([
            'user_id' => $provider->id,
            'space_id' => $dsaOffice->id,
            'role_in_space' => 'member',
        ]);

        // Add 12 more members to DSA Office
        $dsaOfficeMembers = $allUsers->filter(fn($u) => !in_array($u->id, [$mainUser->id, $ecAdmin->id, $provider->id]))
            ->random(12);

        $dsaOfficeMembers->each(function ($user) use ($dsaOffice) {
            \App\Models\SpaceMembership::create([
                'user_id' => $user->id,
                'space_id' => $dsaOffice->id,
                'role_in_space' => 'member',
            ]);
        });

        $allDsaOfficeMembers = collect([$mainUser, $ecAdmin, $provider])->merge($dsaOfficeMembers);

        // Create realistic DSA Office threads and discussions
        $this->seedDsaOfficeContent($dsaOffice, $allDsaOfficeMembers, $ecAdmin, $provider);

        // Create announcement for DSA Office
        \App\Models\Announcement::factory()->create([
            'space_id' => $dsaOffice->id,
            'created_by' => $ecAdmin->id,
            'published_at' => now()->subDays(5),
            'title' => 'Welcome to the DSA Office',
            'body' => "Welcome to the Digital Services Act Office on Eurolink.\n\nThis space is dedicated to supporting digital service providers, online platforms, and very large online platforms (VLOPs) in understanding and complying with the DSA requirements.\n\nHere you can:\n\n- Register your platform and understand your DSA obligations\n- Participate in discussions about content moderation and transparency\n- Learn about risk assessment and mitigation requirements\n- Connect with other platform operators and DSA coordinators\n- Stay updated on enforcement actions and guidance\n\nWhether you're a small platform or a VLOP, we're here to help you navigate the DSA landscape and build a safer digital environment for European users.\n\nFor questions or support, please engage with our team in the discussion threads.",
        ]);

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

    private function seedDsaOfficeContent($dsaOffice, $members, $ecAdmin, $provider): void
    {
        // Thread 1: VLOP Designation and Obligations
        $thread1 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'VLOP Designation - What are our obligations?',
            'created_by' => $provider->id,
            'created_at' => now()->subDays(12),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Our platform has just crossed 45 million monthly active users in the EU. We received a notification that we may be designated as a Very Large Online Platform (VLOP).\n\nWhat additional obligations does this designation bring? We're already compliant with the basic DSA requirements for hosting services.\n\nHow much time do we have to prepare?",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(12),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Congratulations on your growth! Being designated as a VLOP comes with enhanced responsibilities:\n\n**Key VLOP obligations:**\n1. **Risk assessment**: Conduct annual systemic risk assessments\n2. **Risk mitigation**: Implement measures to mitigate identified risks\n3. **Independent audit**: Annual compliance audit by independent auditors\n4. **Transparency reporting**: Enhanced reporting every 6 months\n5. **Crisis response mechanism**: Protocols for addressing crises\n6. **Recommender system transparency**: Explain content recommendation algorithms\n7. **Ad repository**: Maintain searchable repository of advertisements\n8. **Data access for researchers**: Provide vetted researchers access to publicly accessible data\n9. **Compliance function**: Dedicated compliance officer and resources\n\nYou have **4 months** from the designation decision to achieve full compliance.\n\nI recommend scheduling a consultation to discuss your specific situation.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(11),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "That's comprehensive! The risk assessment sounds particularly complex. Do you have templates or frameworks we should follow?\n\nAlso, regarding the compliance officer - are there specific qualifications required?",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(11)->addHours(4),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread1->id,
            'body' => "Yes, we'll be publishing risk assessment guidelines next month. The framework should cover:\n- Dissemination of illegal content\n- Effects on fundamental rights\n- Electoral processes and civic discourse\n- Public health and minors protection\n- Gender-based violence\n- Actual or foreseeable negative impacts\n\nFor the compliance officer: They should be independent, report directly to management, and have the authority and resources to ensure compliance. No specific certifications required, but expertise in digital services regulation is essential.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(10),
        ]);

        // Thread 2: Content Moderation Best Practices
        $thread2 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Content Moderation - Balancing Speed and Accuracy',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(10),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "We operate a social media platform with about 5 million EU users. Our content moderation team struggles to balance speed with accuracy.\n\nThe DSA requires 'timely' decisions on illegal content reports. What's considered timely? And how do we ensure we're not over-removing content?\n\nWe use a mix of automated tools and human review. Any best practices?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(10),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "Great question. 'Timely' isn't defined with a specific number of days - it depends on:\n- The type of illegal content\n- Urgency and severity\n- Technical and operational capacity\n\n**Best practices we recommend:**\n\n1. **Risk-based prioritization**: Prioritize reports based on severity (child sexual abuse material, terrorism content = highest priority)\n\n2. **Clear escalation paths**: Human review for edge cases and appeals\n\n3. **Transparency in decisions**: Always provide clear reasoning for content removal\n\n4. **Error correction mechanisms**: Easy appeals process for users\n\n5. **Regular training**: Keep moderators updated on laws and platform policies\n\n6. **Automation with oversight**: Use AI tools for initial flagging, but maintain human oversight for final decisions on complex cases\n\n7. **Context matters**: Consider context, especially for satire, news reporting, educational content\n\nRemember: The DSA prohibits general monitoring obligations. You're not required to proactively seek out illegal content, but you must act on reports received.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(9),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "This is helpful! Regarding appeals - what's the timeline for handling appeals? And do we need to provide an out-of-court dispute settlement option?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(9)->addHours(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread2->id,
            'body' => "For appeals: No specific timeline mandated, but should be 'without undue delay' and proportionate to the importance/urgency.\n\nYes, you should offer access to certified out-of-court dispute settlement bodies. Users should be able to challenge:\n- Content removal decisions\n- Account suspension/termination\n- Other restrictions on content visibility\n\nThe dispute settlement body must be independent and impartial. We'll be publishing a list of certified bodies soon.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(8),
        ]);

        // Thread 3: Transparency Reporting Requirements
        $thread3 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Transparency Reports - What needs to be included?',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(8),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread3->id,
            'body' => "We're preparing our first DSA transparency report. What metrics and information are mandatory?\n\nWe want to be thorough but also ensure we're meeting the minimum requirements. Any guidance on format or structure?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(8),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread3->id,
            'body' => "Transparency reports must be published at least annually (VLOPs: every 6 months) and include:\n\n**Content moderation information:**\n- Number of orders received from authorities to act against illegal content\n- Number of notices submitted (by type of alleged illegal content)\n- Actions taken based on notices\n- Number of complaints received through internal complaint system\n- Decisions on complaints\n- Use of automated means for content moderation\n- Average time for taking action\n\n**For hosting services, add:**\n- Number of suspensions/terminations\n- Number of items removed or disabled\n\n**For online platforms, also include:**\n- Number of disputes submitted to out-of-court settlement\n- Outcome of dispute settlement\n\n**Format:** Must be publicly available, machine-readable, and accessible. We recommend publishing on your website.\n\nWe're working on a standardized template to make this easier.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(7),
        ]);

        // Thread 4: Recommender Systems and Algorithm Transparency
        $thread4 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Recommender Systems - Transparency Requirements',
            'created_by' => $provider->id,
            'created_at' => now()->subDays(7),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "Our platform uses machine learning algorithms to recommend content to users. Under the DSA, what do we need to disclose about our recommendation systems?\n\nWe're concerned about protecting proprietary algorithms while meeting transparency obligations. Where's the balance?",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(7),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "You need to provide clear information about:\n\n1. **Main parameters** used in recommender systems\n2. **Options for users** to modify or influence those parameters\n3. **Why content is recommended** to users (at least one option for not based on profiling)\n\n**What to disclose:**\n- Signals/criteria most significant in determining recommendations\n- How user actions influence recommendations\n- If/how user data is used\n\n**What NOT required:**\n- Detailed algorithmic source code\n- Trade secrets or proprietary information\n- Exact weights or formulas\n\n**Key requirement:** Users must have at least one option for content ranking NOT based on profiling (e.g., chronological feed, most popular in general, category-based).\n\nThink of it as explaining 'what' influences recommendations without revealing 'how' the algorithm works technically.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(6),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "That clarifies things! So we could say something like 'Content is recommended based on: your past engagement, content recency, content from accounts you follow, and trending topics' without revealing the exact algorithm?\n\nAnd we need to provide a chronological or non-personalized option?",
            'created_by' => $provider->id,
            'created_at' => now()->subDays(6)->addHours(3),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread4->id,
            'body' => "Exactly! That level of explanation is appropriate. And yes, at least one non-profiled option must be available and easily accessible to users.\n\nFor VLOPs, there's an additional requirement: users should be able to easily access and understand why specific content was recommended to them individually.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(5),
        ]);

        // Thread 5: Trusted Flaggers Program
        $thread5 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Trusted Flaggers - How does the program work?',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(5),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread5->id,
            'body' => "The DSA mentions 'trusted flaggers' that platforms should prioritize. How do we identify trusted flaggers? Is there a certification process?\n\nWhat kind of priority treatment are we expected to provide them?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(5),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread5->id,
            'body' => "Trusted flaggers are entities with particular expertise and competence in detecting illegal content. They're awarded this status by Digital Services Coordinators.\n\n**Criteria for trusted flagger status:**\n- Particular expertise and competence\n- Independence from platforms\n- Accuracy of notices submitted\n- Diligent exercise of activities\n- Representation of collective interests\n\n**Your obligations toward trusted flaggers:**\n- Process notices 'without undue delay'\n- Give priority consideration\n- Expedited review process\n- Decisions communicated without delay\n\n**What this means practically:**\n- Set up a dedicated reporting channel for trusted flaggers\n- Faster response times than general user reports\n- Direct communication channels\n- Regular feedback on notices submitted\n\nThe Commission maintains a public database of trusted flaggers. You can check there to verify status.\n\nThis doesn't mean automatically removing content they flag - you still need to assess each notice, but with priority timing.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(4),
        ]);

        // Thread 6: Online Advertising Transparency
        $thread6 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Online Advertising - Transparency Requirements',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(4),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "We display advertisements on our platform. What transparency requirements apply to online advertising under the DSA?\n\nDo we need to build an ad repository? What information needs to be included?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(4),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "All online platforms displaying ads must provide users with clear info for each ad:\n\n**Real-time disclosure (must be visible with the ad):**\n- That it's advertising (clear marking)\n- Who paid for the ad (sponsor)\n- Meaningful info about main parameters determining why the ad was shown to them\n\n**For VLOPs specifically:**\nMust maintain a publicly accessible, searchable **ad repository** containing:\n- Content of the ad\n- Sponsor identity\n- Period of presentation\n- Whether targeted and main parameters\n- Total number of recipients reached\n\nThe repository must keep ads for **1 year** after last display.\n\n**Prohibited:**\n- Ads based on profiling using special categories of data (race, political opinions, religion, health, sexual orientation, etc.)\n- Targeted advertising to minors based on profiling\n\nThis helps users understand why they see certain ads and provides researchers/regulators visibility into ad practices.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(3),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "Regarding 'meaningful info about parameters' - can you give an example of what this looks like in practice?\n\nAnd for the ad repository, does it need to be integrated into our platform or can it be a separate database?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(3)->addHours(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread6->id,
            'body' => "**Example of parameter disclosure:**\n'This ad is shown to you based on: your location (Berlin), your age range (25-34), and that you recently viewed sports content.'\n\nOr: 'This ad is shown to all users in Germany interested in technology.'\n\nIt doesn't need to be exhaustive - just the *main* parameters.\n\n**Ad repository:**\nCan be integrated or separate, as long as it's:\n- Publicly accessible\n- Machine-readable\n- Searchable (by keywords, advertiser, date range, etc.)\n- Accessible via API is recommended for researcher access\n\nMany platforms create a dedicated subdomain (e.g., ads.yourplatform.com) for this purpose.",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(2),
        ]);

        // Thread 7: Cross-border Cooperation and Enforcement
        $thread7 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Multi-country Operations - Which DSA coordinator applies?',
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread7->id,
            'body' => "Our platform operates across multiple EU member states. We have offices in Ireland, Germany, and the Netherlands.\n\nWhich Digital Services Coordinator (DSC) has jurisdiction over us? Do we need to work with multiple DSCs?\n\nHow does cross-border enforcement work?",
            'created_by' => $members->random()->id,
            'created_at' => now()->subDays(2),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread7->id,
            'body' => "Good question! The DSA uses a **country of establishment** principle:\n\n**Your 'home' DSC** is in the Member State where your main establishment is located (where you make principal decisions about service provision).\n\n**For VLOPs/VLOSEs:**\nThe European Commission has direct supervisory and enforcement powers, though your DSC of establishment remains your primary contact.\n\n**Cross-border mechanism:**\n- Your DSC of establishment is your main point of contact\n- Other Member States can request your DSC to investigate\n- DSCs cooperate through the European Board for Digital Services\n- All DSCs can request information and conduct investigations in urgent cases\n\n**Your obligations:**\n- Designate a single point of contact (legal representative in your country of establishment)\n- Respond to all DSCs when requested\n- Only one DSC can impose penalties for the same conduct (coordination mechanism)\n\nIn practice: Identify your main establishment, notify that DSC, and they'll guide you on coordination with other authorities.\n\nWhere do you make your principal strategic decisions?",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subDays(1),
        ]);

        // Thread 8: Protection of Minors
        $thread8 = \App\Models\Thread::create([
            'space_id' => $dsaOffice->id,
            'title' => 'Protection of Minors - Age Verification and Safety',
            'created_by' => $provider->id,
            'created_at' => now()->subHours(20),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread8->id,
            'body' => "Our platform is accessible to minors. What are our obligations under the DSA regarding child safety?\n\nDo we need age verification? Are there restrictions on recommender systems or advertising for minors?",
            'created_by' => $provider->id,
            'created_at' => now()->subHours(20),
        ]);

        \App\Models\Post::create([
            'thread_id' => $thread8->id,
            'body' => "The DSA has specific provisions for protecting minors:\n\n**All platforms accessible to minors must:**\n1. Take appropriate measures to ensure high level of privacy, safety, and security for minors\n2. Not present ads based on profiling using minors' personal data\n\n**VLOPs accessible to minors must:**\n1. Assess systemic risks specifically related to minors\n2. Put in place appropriate mitigation measures\n3. Design systems with safety by design principles\n\n**Best practices (not legally required but recommended):**\n- Age-appropriate content moderation\n- Enhanced privacy defaults for minor accounts\n- Parental controls\n- Easy reporting mechanisms for child safety concerns\n- Staff training on child safety\n\n**Age verification:**\nThe DSA doesn't mandate specific age verification, but you need *reasonable efforts* to:\n- Identify minor users to apply protections\n- Prevent profiling for ads\n- Apply appropriate safety measures\n\nMany platforms use age gates, parental consent mechanisms, or age estimation technologies.\n\n**Important:** Any age verification must itself comply with GDPR and minimize data collection.\n\nWhat type of content/services does your platform provide?",
            'created_by' => $ecAdmin->id,
            'created_at' => now()->subHours(14),
        ]);
    }
}
