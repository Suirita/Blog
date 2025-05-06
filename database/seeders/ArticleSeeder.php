<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title'       => 'Writing Clean Code: Best Practices for Efficient Programming',
                'content'     => "<h2>Writing Clean Code: Best Practices for Efficient Programming</h2><p>Clean code is more than just writing software that works—it’s about writing software that is understandable, maintainable, and scalable. Whether you're working solo or on a large team, following good coding practices leads to fewer bugs, better collaboration, and faster development cycles.</p><h3>1. Descriptive Naming</h3><p>Using meaningful and descriptive names for variables, functions, and classes helps convey the purpose and use without needing excessive comments. Avoid abbreviations and choose clarity over brevity.</p><h3>2. Follow the DRY Principle</h3><p>Don't Repeat Yourself (DRY) encourages reusability and abstraction. If you find similar blocks of code repeated, consider refactoring them into reusable functions or services.</p><h3>3. Single Responsibility Principle</h3><p>Each function or class should have one responsibility. This reduces complexity and makes testing easier. For example, a function that fetches data shouldn't also be formatting the output.</p><h3>4. Use Comments Strategically</h3><p>Comments should explain the 'why' behind a decision, not the 'what'—the code should be self-explanatory. Avoid redundant comments that add no value.</p><h3>5. Write Automated Tests</h3><p>Unit tests, integration tests, and end-to-end tests ensure your code works as expected and gives you the confidence to refactor without breaking existing functionality.</p><p>Ultimately, clean code reflects professionalism and care. It benefits your team, future maintainers, and most importantly—your users.</p>",
                'views'       => 10,
                'user_id'      => 1,
                'category_id'  => 1,
            ],
            [
                'title'       => 'The Art of Design: Balancing Creativity and Functionality',
                'content'     => "<h2>The Art of Design: Balancing Creativity and Functionality</h2><p>Design is where creativity meets usability. A well-designed product isn’t just attractive—it solves problems and guides the user through an intuitive experience. Here are key principles to master the art of design:</p><h3>1. Understand Your Users</h3><p>Start with empathy. Conduct user interviews, gather data, and map out user journeys. Design decisions should be driven by real needs, not assumptions.</p><h3>2. Prioritize Accessibility</h3><p>Inclusive design ensures your product is usable by as many people as possible. Use sufficient contrast, legible fonts, and clear navigation. Test with screen readers and keyboard navigation.</p><h3>3. Balance Aesthetics with Utility</h3><p>Great design is both beautiful and functional. Use color, typography, and layout strategically to enhance usability, not distract from it.</p><h3>4. Iterate and Improve</h3><p>Design is never done. Prototype quickly, gather feedback, and refine continuously. A/B testing can be a valuable tool to validate design choices.</p><p>Design that achieves both beauty and utility creates trust, engagement, and satisfaction.</p>",
                'views'       => 5,
                'user_id'      => 1,
                'category_id'  => 2,
            ],
            [
                'title'       => 'Mindful Living: Simple Habits for a Balanced Life',
                'content'     => "<h2>Mindful Living: Simple Habits for a Balanced Life</h2><p>Mindfulness is a powerful tool to help manage stress, increase focus, and improve overall well-being. It doesn’t require hours of meditation—small daily habits can make a big difference.</p><h3>1. Begin with Breath</h3><p>Start your day with a few minutes of deep breathing. This anchors you in the present and sets a calm tone for what’s ahead.</p><h3>2. Practice Gratitude</h3><p>Each day, write down three things you’re thankful for. This rewires the brain to focus on positivity and abundance.</p><h3>3. Create Digital Boundaries</h3><p>Limit screen time, especially in the morning and before bed. Use this time to connect with people, read, or go for a walk.</p><h3>4. Mindful Eating</h3><p>Eat without distractions. Savor each bite, notice textures and flavors, and pay attention to hunger and fullness signals.</p><h3>5. Nighttime Reflection</h3><p>Before sleep, take a moment to reflect on the day. What went well? What would you change? This builds self-awareness and emotional clarity.</p><p>Incorporating mindfulness into your routine fosters a calmer, more intentional life.</p>",
                'views'       => 15,
                'user_id'      => 1,
                'category_id'  => 3,
            ],
            [
                'title'       => 'The Future of Technology: Innovations Shaping Our World',
                'content'     => "<h2>The Future of Technology: Innovations Shaping Our World</h2><p>Technology is evolving at a breakneck pace, reshaping how we live, work, and connect. The following innovations are poised to redefine our future:</p><h3>1. Artificial Intelligence (AI)</h3><p>From chatbots to medical diagnosis, AI is revolutionizing automation and decision-making. Responsible AI development is crucial to avoid bias and ensure transparency.</p><h3>2. Blockchain and Decentralized Systems</h3><p>Blockchain introduces new possibilities in finance, identity, and governance. Trustless systems can reduce fraud and increase accountability.</p><h3>3. Green Technologies</h3><p>As climate change accelerates, green tech like solar energy, carbon capture, and electric vehicles are vital for sustainability.</p><h3>4. Extended Reality (XR)</h3><p>AR, VR, and MR will enhance education, entertainment, and remote work, making digital experiences more immersive and engaging.</p><h3>5. Quantum Computing</h3><p>Still in its infancy, quantum computing could revolutionize cryptography, materials science, and problem-solving on a scale we’ve never seen before.</p><p>The key is not just innovation, but ethical and inclusive innovation that benefits all humanity.</p>",
                'views'       => 20,
                'user_id'      => 1,
                'category_id'  => 4,
            ],
            [
                'title'       => 'Healthy Living: Simple Steps to a Stronger Body and Mind',
                'content'     => "<h2>Healthy Living: Simple Steps to a Stronger Body and Mind</h2><p>Health isn’t just the absence of illness—it’s a proactive state of physical, mental, and emotional well-being. Here are sustainable steps toward holistic health:</p><h3>1. Nourish with Intention</h3><p>Eat whole, nutrient-dense foods. Prioritize vegetables, healthy fats, lean proteins, and stay hydrated throughout the day.</p><h3>2. Stay Active Daily</h3><p>You don’t need a gym. Walking, stretching, bodyweight workouts, or dancing are excellent ways to keep moving.</p><h3>3. Prioritize Rest</h3><p>Aim for 7–9 hours of sleep nightly. Create a bedtime ritual: limit screens, dim lights, and relax with a book or meditation.</p><h3>4. Manage Stress Mindfully</h3><p>Use breathing exercises, journaling, or creative hobbies to unwind. Avoid bottling up emotions—talk with friends or a counselor.</p><h3>5. Build Social Connections</h3><p>Strong relationships boost emotional resilience. Invest time in loved ones and seek out community groups or activities.</p><p>Healthy living is a lifelong journey of small, consistent choices. The benefits ripple into every area of life.</p>",
                'views'       => 8,
                'user_id'      => 1,
                'category_id'  => 5,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
