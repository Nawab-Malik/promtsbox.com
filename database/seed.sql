INSERT INTO categories (name, slug, description, sort_order, is_featured, status) VALUES
('AI Prompt Library', 'ai-prompts', 'Copy-ready AI prompts for images and text.', 1, 1, 'published'),
('Instagram & TikTok Tools', 'instagram-tiktok', 'Free tools and guides for social creators.', 2, 1, 'published'),
('Text & Writing Tools', 'text-writing', 'Headlines, captions, and writing helpers.', 3, 1, 'published'),
('Photo & Profile Ideas', 'photo-profile', 'Pose and profile picture inspiration.', 4, 0, 'published'),
('How-To Guides', 'how-to', 'Step-by-step AI and social media tutorials.', 5, 1, 'published'),
('Festivals & Seasons', 'festivals', 'Seasonal prompts and captions.', 6, 0, 'published'),
('Business & Etsy', 'business-etsy', 'Product photos and listing ideas.', 7, 0, 'published'),
('Blog & Updates', 'updates', 'News and tips from PromptsBox.', 8, 0, 'published');

INSERT INTO pages (title, slug, content, template, status, meta_title, meta_description, show_in_footer, show_in_header, sort_order) VALUES
('About Us', 'about', '<p>PromptsBox helps creators find AI prompts and simple tools. Add your story here from the admin panel.</p>', 'default', 'published', 'About PromptsBox', 'Learn about PromptsBox — free AI prompts and creator tools.', 1, 1, 1),
('Contact Us', 'contact', '<p>Send us a message using the form below.</p>', 'contact', 'published', 'Contact PromptsBox', 'Contact the PromptsBox team.', 1, 1, 2),
('Privacy Policy', 'privacy-policy', '<p>Update this privacy policy before applying for AdSense. Mention cookies, analytics, and advertising.</p>', 'default', 'published', 'Privacy Policy', 'PromptsBox privacy policy.', 1, 0, 3),
('Terms of Service', 'terms-of-service', '<p>Your terms of use content goes here.</p>', 'default', 'published', 'Terms of Service', 'PromptsBox terms of service.', 1, 0, 4),
('Disclaimer', 'disclaimer', '<p>AI tools generate suggestions only. Users are responsible for how they use outputs.</p>', 'default', 'published', 'Disclaimer', 'PromptsBox disclaimer.', 1, 0, 5);

INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'PromptsBox'),
('site_tagline', 'Free AI prompts, tools & guides for creators'),
('default_meta_title', 'PromptsBox — AI Prompts & Free Creator Tools'),
('default_meta_description', 'Browse AI prompt libraries, free Instagram tools, and step-by-step guides for creators.'),
('google_site_verification', ''),
('analytics_id', ''),
('adsense_client_id', ''),
('adsense_enabled', '0'),
('robots_extra', ''),
('maintenance_mode', '0'),
('contact_email', 'hello@promptsbox.com'),
('social_twitter', ''),
('social_facebook', ''),
('social_instagram', ''),
('organization_logo', ''),
('cookie_consent_enabled', '1');

INSERT INTO tools (name, slug, description, long_content, tool_type, config_json, category_id, status, sort_order, meta_title, meta_description) VALUES
('Instagram Bio Generator', 'instagram-bio-generator', 'Create a short bio in seconds.', '<p>Use this free Instagram bio generator to craft a catchy profile bio. Pick your vibe and copy the result.</p>', 'bio_generator', '{}', 2, 'published', 1, 'Instagram Bio Generator', 'Free Instagram bio generator — copy and paste.'),
('AI Prompt Builder', 'ai-prompt-builder', 'Build structured image prompts.', '<p>Combine subject, style, and lighting into a ready-to-copy AI image prompt.</p>', 'prompt_builder', '{}', 1, 'published', 2, 'AI Prompt Builder', 'Build AI image prompts with dropdowns.'),
('Caption Generator', 'caption-generator', 'Captions for posts and reels.', '<p>Generate caption ideas for your next post.</p>', 'caption_generator', '{}', 2, 'published', 3, 'Caption Generator', 'Free social media caption generator.'),
('Hashtag Helper', 'hashtag-helper', 'Hashtag ideas by topic.', '<p>Get starter hashtag sets for Instagram and TikTok.</p>', 'hashtag_helper', '{}', 2, 'published', 4, 'Hashtag Helper', 'Free hashtag suggestion tool.'),
('SEO Meta Generator', 'seo-meta-generator', 'Generate title and meta description ideas.', '<p>Use this free meta title and description generator before publishing posts.</p>', 'meta_generator', '{}', 3, 'published', 5, 'SEO Meta Generator', 'Free SEO title and meta description generator.');