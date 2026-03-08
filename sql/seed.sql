USE `news_db`;

-- Users
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `is_active`) VALUES
('admin', 'admin@news.local', '$2y$12$NU6z.vuuylg2co8aHE7hl.U4JmyNx9EsZB3VvZ3PZA8egRBPj3UWm', 'admin', 1),
('editor', 'editor@news.local', '$2y$12$nRaieekyrhpaqzOwU2Gi7ucjhkOA7FDhMnl0J5o3/2ySp82w3vf56', 'editor', 1),
('author', 'author@news.local', '$2y$12$h0Ug74PBFhWz5Aj6/jy7AeVSxa9WLjjUy/BJzp4VJPaR4QLE4eqn2', 'author', 1);

-- Categories
INSERT INTO `categories` (`name`, `slug`, `description`) VALUES
('Politics', 'politics', 'Current political events and news from around the world.'),
('Technology', 'technology', 'Latest updates on gadgets, software, and technological advancements.'),
('Sports', 'sports', 'Scores, analysis, and news from various sporting events.'),
('Health', 'health', 'Wellness tips, medical breakthroughs, and healthy living.'),
('Business', 'business', 'Market trends, corporate news, and financial analysis.'),
('Entertainment', 'entertainment', 'Movies, music, celebrity news, and pop culture.');

-- Tags
INSERT INTO `tags` (`name`, `slug`) VALUES
('Breaking News', 'breaking-news'),
('Innovation', 'innovation'),
('Elections', 'elections'),
('Fitness', 'fitness'),
('Startups', 'startups'),
('Hollywood', 'hollywood'),
('Global', 'global'),
('Local', 'local');

-- Articles
INSERT INTO `articles` (`title`, `slug`, `excerpt`, `content`, `image_url`, `author_id`, `category_id`, `status`, `views`, `published_at`) VALUES
('New Global Summit Announced', 'new-global-summit-announced', 'World leaders are set to meet next month to discuss climate change.', 'World leaders have officially announced a new global summit set to take place next month. The primary focus of this unprecedented gathering will be tackling the escalating crisis of climate change. \n\nExperts believe that this could be a turning point in international relations, as countries that previously hesitated to commit to carbon reduction targets are now showing willingness to cooperate.', 'summit.jpg', 3, 1, 'published', 1542, '2023-10-01 09:00:00'),
('Tech Giant Unveils Latest Smartphone', 'tech-giant-unveils-latest-smartphone', 'The highly anticipated device features a revolutionary new display.', 'In a stunning presentation today, the leading tech giant unveiled its latest flagship smartphone. The device boasts a revolutionary edge-to-edge display that critics are already calling "game-changing." \n\nBeyond the screen, the phone features a significantly upgraded camera system and a battery life that promises to outlast any competitor on the market. Pre-orders begin this Friday.', 'smartphone.jpg', 2, 2, 'published', 4521, '2023-10-02 10:30:00'),
('Championship Finals Reach Climax', 'championship-finals-reach-climax', 'The underdog team pulls off a miraculous victory in the final seconds.', 'Fans were left breathless after last night\'s championship finals, where the underdog team managed to pull off a miraculous victory in the closing seconds of the game. \n\nThe star player delivered a performance for the ages, sinking the winning shot just before the buzzer. The city erupted in celebration, marking the end of a legendary season.', 'sports.jpg', 3, 3, 'published', 8900, '2023-10-03 21:15:00'),
('New Study Highlights Benefits of Daily Walking', 'new-study-highlights-benefits-of-daily-walking', 'Just 30 minutes a day can significantly improve your cardiovascular health.', 'A comprehensive new study published in the Medical Journal has highlighted the profound benefits of daily walking. According to researchers, just 30 minutes of brisk walking each day can drastically reduce the risk of cardiovascular disease. \n\nThe study followed over 10,000 participants over a decade, providing robust evidence that simple lifestyle changes can have long-lasting impacts on overall health and longevity.', 'walking.jpg', 2, 4, 'published', 2341, '2023-10-04 08:45:00'),
('Stock Market Hits Record High', 'stock-market-hits-record-high', 'Investors celebrate as major indices break previous records.', 'The stock market reached unprecedented heights today as major indices broke through their previous all-time records. Analysts attribute this surge to strong corporate earnings reports and a stabilizing global economy. \n\nWhile some experts warn of a potential correction in the near future, the general sentiment among investors remains overwhelmingly positive.', 'market.jpg', 3, 5, 'published', 3120, '2023-10-05 16:20:00'),
('Award-Winning Director Announces Next Film', 'award-winning-director-announces-next-film', 'The upcoming sci-fi epic is expected to begin production next year.', 'Following the massive success of her last movie, the award-winning director has officially announced her next project: a sweeping sci-fi epic. \n\nCasting is currently underway, with several A-list actors rumored to be in talks for the lead roles. Production is slated to begin early next year, with a tentative release date set for the following summer.', 'movie.jpg', 2, 6, 'published', 1890, '2023-10-06 14:00:00'),
('Local Election Results In', 'local-election-results-in', 'Surprising turnout leads to unexpected victories in several districts.', 'The results from yesterday\'s local elections are finally in, and the surprising voter turnout has led to several unexpected victories across key districts. \n\nChallengers managed to unseat several long-term incumbents, signaling a clear desire for change among the electorate. The newly elected officials will be sworn in next month.', 'election.jpg', 3, 1, 'published', 1205, '2023-10-07 07:30:00'),
('AI Breakthrough Promises Faster Medical Diagnoses', 'ai-breakthrough-promises-faster-medical-diagnoses', 'New algorithms can detect diseases with greater accuracy than ever before.', 'Researchers have announced a significant breakthrough in artificial intelligence that promises to revolutionize medical diagnoses. The new algorithm has demonstrated the ability to detect early signs of diseases with a higher accuracy rate than human specialists. \n\nHospitals are already preparing to integrate this technology into their diagnostic procedures, which could save countless lives through earlier intervention.', 'ai-med.jpg', 2, 2, 'published', 5630, '2023-10-08 11:15:00'),
('Star Athlete Signs Record Contract', 'star-athlete-signs-record-contract', 'The multi-year deal is the largest in the history of the sport.', 'In a move that has stunned the sports world, the star athlete has officially signed a record-breaking multi-year contract with the rival team. \n\nThe deal, reported to be the largest in the history of the sport, includes unprecedented guaranteed money and performance bonuses. Fans are eagerly awaiting his debut in the new uniform.', 'contract.jpg', 3, 3, 'published', 7420, '2023-10-09 13:45:00'),
('Mental Health Awareness Week Begins', 'mental-health-awareness-week-begins', 'Organizations nationwide launch campaigns to reduce stigma.', 'Today marks the beginning of Mental Health Awareness Week. Organizations across the country are launching various campaigns aimed at reducing the stigma surrounding mental health issues. \n\nEvents include free counseling sessions, public seminars, and widespread social media initiatives to encourage open conversations about mental well-being.', 'mental-health.jpg', 2, 4, 'published', 2100, '2023-10-10 09:00:00'),
('Startup Disrupts Delivery Industry', 'startup-disrupts-delivery-industry', 'Innovative logistics model promises faster and cheaper deliveries.', 'A new startup has quietly emerged from stealth mode to disrupt the traditional delivery industry. Utilizing an innovative logistics model and a fleet of autonomous vehicles, the company promises faster and cheaper deliveries. \n\nInvestors have already poured millions into the venture, betting heavily on its potential to challenge established industry giants.', 'startup.jpg', 3, 5, 'published', 1850, '2023-10-11 10:20:00'),
('Pop Icon Releases Surprise Album', 'pop-icon-releases-surprise-album', 'The new 12-track record dropped at midnight to fan frenzy.', 'Without any prior announcement, the global pop icon released a surprise 12-track album at midnight, sending fans into an absolute frenzy. \n\nThe album, which explores darker and more mature themes than her previous work, immediately shot to the top of the streaming charts. Critics are praising the bold new direction.', 'album.jpg', 2, 6, 'published', 9900, '2023-10-12 00:05:00'),
('Draft: Future Space Missions', 'draft-future-space-missions', 'Exploring the possibilities of manned missions to Mars.', 'This is a draft article exploring the upcoming plans for space exploration, focusing primarily on the proposed manned missions to Mars in the next decade. \n\nFurther research and interviews with aerospace engineers will be added before publication.', NULL, 3, 2, 'draft', 0, NULL),
('Draft: Economic Forecast 2024', 'draft-economic-forecast-2024', 'Predicting the market trends for the upcoming year.', 'An early draft of the economic forecast for 2024. Currently compiling data from various financial institutions to provide a comprehensive overview of expected market behaviors. \n\nWill include sections on inflation, housing, and tech stocks.', NULL, 2, 5, 'draft', 0, NULL),
('Archived: 2020 Olympics Recap', 'archived-2020-olympics-recap', 'A look back at the highlights of the summer games.', 'This article provides a comprehensive recap of the 2020 Summer Olympics, highlighting the most memorable moments, record-breaking performances, and inspiring stories of the athletes. \n\nThis content has been archived for historical reference.', 'olympics.jpg', 3, 3, 'archived', 12500, '2021-08-15 10:00:00');

-- Article Tags
INSERT INTO `article_tags` (`article_id`, `tag_id`) VALUES
(1, 1), (1, 7),
(2, 2), (2, 7),
(3, 8),
(4, 4), (4, 7),
(5, 7),
(6, 6),
(7, 3), (7, 8),
(8, 2), (8, 7),
(9, 1),
(10, 4), (10, 8),
(11, 2), (11, 5),
(12, 1), (12, 6);

-- Comments
INSERT INTO `comments` (`article_id`, `name`, `email`, `content`, `is_approved`) VALUES
(1, 'John Doe', 'john@example.com', 'I really hope they make some concrete decisions this time.', 1),
(1, 'Jane Smith', 'jane@example.com', 'Another summit, another set of empty promises.', 1),
(2, 'Tech Nerd', 'nerd@example.com', 'Can\'t wait to get my hands on this! The display looks amazing.', 1),
(2, 'Apple Fan', 'fan@example.com', 'Looks like a copy of last year\'s model to be honest.', 0),
(3, 'Sports Fanatic', 'sportsfan@example.com', 'Best game I\'ve ever seen in my life!', 1),
(4, 'Health Nut', 'healthy@example.com', 'Walking is so underrated. Great article.', 1),
(5, 'Investor Pro', 'investor@example.com', 'Time to sell or hold? The market is crazy right now.', 1),
(7, 'Local Resident', 'resident@example.com', 'Glad to see some new faces in the city council.', 1),
(8, 'Dr. Roberts', 'drroberts@example.com', 'This could be a very useful tool, but human oversight is still necessary.', 0),
(12, 'Music Lover', 'music@example.com', 'I haven\'t stopped listening since midnight. A masterpiece!', 1);
