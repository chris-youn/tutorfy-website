<?php
include('../adminModule/configuration.php');

session_start();

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getProfileOptions() {
    if (isUserLoggedIn()) {
        return '
                <a href="../login/profile.php">View Profile</a>
                <a href="../login/logout.php">Sign Out</a>
                ';
    } else {
        return '
                <a href="../login/login.php">Sign In</a>
                <a href="../login/register.php">Sign Up</a>
                ';
    }
}
?>

<!DOCTYPE HTML>

<html lang="en">
    <head>
        <title>Tutorfy | Articles</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../global.css">
        <link rel="stylesheet" type="text/css" href="article.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
        <script src="article.js" defer></script>
        <script src="../global.js" defer></script>
    </head>

<body>
    <header class="topnav">
        <div class="logo">
            <img src="logo.png" alt="Logo">
            <span>Tutorfy</span>
        </div>
        <nav class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link">Home</a>
            <a href="../article/article.php" class="nav-link active">Articles</a>
            <a href="../store/store.php" class="nav-link">Store</a>
            <a href="../forum/forum.php" class="nav-link">Forums</a>
            <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
        </nav>
        <div class="icons">
            <div class="container">
                <span class="icon" id="cartIcon">🛒<span id="cartBadge" class="badge">0</span></span>
                <div id="shopping-cart" class="shopping-cart" style="display:none;">
                    <div class="shopping-cart-header">
                        <div class="shopping-cart-total">
                            <span id="totalText" class="light-text">Total: $0</span>
                        </div>
                    </div>
                    <ul class="shopping-cart-items" id="items">
                        <li id="tutorSessionListItem">
                                    <div id='tutorSessionCartShort'></div>
                                    <button id="tutorSessionClear">X</button>
                                    <button id="tutorSessionRemove">-</button>
                                    <button id="tutorSessionAdd">+</button>
                                </li>
                                <li id="tutorSessionLongListItem">
                                    <div id='tutorSessionCartLong'></div>
                                    <button id="tutorSessionLongClear">X</button>
                                    <button id="tutorSessionLongRemove">-</button>
                                    <button id="tutorSessionLongAdd">+</button>
                                </li>
                                <li id="tutorSessionShortBulkListItem">
                                    <div id='tutorSessionCartShortBulk'></div>
                                    <button id="tutorSessionShortBulkClear">X</button>
                                    <button id="tutorSessionShortBulkRemove">-</button>
                                    <button id="tutorSessionShortBulkAdd">+</button>
                                </li>
                                <li id="tutorSessionLongBulkListItem">
                                    <div id='tutorSessionCartLongBulk'></div>
                                    <button id="tutorSessionLongBulkClear">X</button>
                                    <button id="tutorSessionLongBulkRemove">-</button>
                                    <button id="tutorSessionLongBulkAdd">+</button>
                                </li>
                        
                    </ul>
                    <form action="../cart/cart.php" method="post">
                        <div class="checkout">
                            <input id="cartCheckout" type="submit" value="Checkout"></input>
                        </div>
                    </form>
                </div>
            </div>
            <div class="profileMenu">
                <span class="profileIcon">👤</span>
                    <div class="profileMenuContent">
                        <?php echo getProfileOptions() ?>
                    </div>
            </div>
        </div>
    </header>
                        
        <main class="content">
            <div class="articles-section">
                <h1>Articles</h1>
                <div class="articles">
                    <div class="article" id="article1"> <!-- onclick="location.href='article1.html';" -->
                        <h2>How to Write an Essay: A Comprehensive Guide</h2>
                        <p><em>By John Doe and ChatGPT</em> 05/05/2024</p>
                        <p>
                        Writing an essay is a fundamental skill for students and professionals alike, offering a 
                        structured way to convey ideas and arguments. The process begins with understanding the 
                        essay's purpose and the audience. An essay can serve various purposes, such as to inform, 
                        persuade, or entertain. Knowing the intended audience helps tailor the language, tone, and 
                        structure accordingly. This foundational step sets the stage for a coherent and impactful piece.
                        </p>
                        <p>
                        The next step is to choose a topic and conduct thorough research. A well-defined topic provides 
                        a clear focus for the essay. Research involves gathering relevant information from credible sources, 
                        taking notes, and organizing the data systematically. This phase is crucial as it forms the backbone 
                        of the essay's content. Quality research ensures that the arguments presented are well-supported by 
                        facts, statistics, and expert opinions, which enhances the essay's credibility and persuasiveness.
                        </p>
                        <p>
                        Once the research is complete, the essay outline is created. An effective outline includes an introduction,
                        body paragraphs, and a conclusion. The introduction should grab the reader's attention and provide a 
                        preview of what the essay will cover. A strong thesis statement, typically included in the introduction, 
                        succinctly expresses the main argument or purpose of the essay. Each body paragraph should focus on a 
                        single point that supports the thesis, starting with a topic sentence followed by evidence and analysis. 
                        Transitions between paragraphs should be smooth, ensuring the essay flows logically. 
                        The conclusion should summarize the key points and restate the thesis in light of the evidence discussed, 
                        providing a final perspective on the topic.
                        </p> 
                        <p>
                        Drafting the essay involves expanding the outline into full sentences and paragraphs. This is where the 
                        writer's voice and style come into play. It's essential to maintain clarity and coherence throughout 
                        the essay. Active voice, varied sentence structures, and precise vocabulary enhance readability and engagement. 
                        After completing the first draft, revising and editing are crucial steps. Revision focuses on content and 
                        structure, ensuring that the essay addresses the topic comprehensively and logically. Editing, on the other 
                        hand, focuses on grammar, punctuation, and spelling errors, ensuring the essay is polished and professional.
                        </p>
                        <p>
                        In conclusion, writing an essay is a systematic process that involves careful planning, research, outlining, 
                        drafting, and revising. By following these steps, writers can effectively communicate their ideas and arguments, 
                        producing essays that are informative, persuasive, and engaging. Whether for academic assignments, professional 
                        reports, or personal expression, mastering the art of essay writing is a valuable skill that enhances one's 
                        ability to convey complex ideas with clarity and precision.
                        </p>
                        <p>
                        <em>This article was created in conjunction with the generative AI, ChatGPT.</em>
                        </p>
                    </div>
                    <div class="article" id="article2"> <!-- onclick="location.href='article2.html';" -->
                        <h2>Understanding Multiplication and Division: A Kid-Friendly Guide</h2>
                        <p><em>By Jane Doe and ChatGPT</em> 05/05/2024</p>
                        <p>Hey there, young math explorer! Today, we're going to learn about two very important math operations: multiplication and division. 
                        They might sound big and complicated, but once you understand the basics, you'll see they're not so tricky after all. Let's dive in and 
                        discover how these cool math tools work!</p>
                        
                        <h3>Multiplication: Making Groups Bigger</h3>
                        <p>Think of multiplication as a way to add the same number over and over again. It's like having several groups of the same size. 
                        For example, if you have 3 groups of 4 apples, how many apples do you have in total? Instead of adding 4 + 4 + 4, you can multiply!</p>
                        <p>Here's how it works:</p>
                        <ul>
                            <li>3 groups of 4 apples means you have 3 times 4.</li>
                            <li>We write it like this: 3 × 4.</li>
                            <li>And the answer is 12, because 3 times 4 equals 12.</li>
                        </ul>
                        <p>So, 3 × 4 = 12. Easy, right? Multiplication helps you find out how many items you have in total when you have equal groups.</p>
                        
                        <h3>Division: Sharing Fairly</h3>
                        <p>Now, let's talk about division. Division is like sharing or splitting something into equal parts. Imagine you have 12 cookies and 3 friends. 
                        You want to share the cookies so everyone gets the same amount. How many cookies does each friend get?</p>
                        <p>Here's how division helps:</p>
                        <ul>
                            <li>You have 12 cookies and want to divide them among 3 friends.</li>
                            <li>We write it like this: 12 ÷ 3.</li>
                            <li>And the answer is 4, because 12 divided by 3 equals 4.</li>
                        </ul>
                        <p>So, 12 ÷ 3 = 4. Each friend gets 4 cookies. Division helps you figure out how many items each person gets when you share equally.</p>
                        
                        <h3>Practice Makes Perfect</h3>
                        <p>To get really good at multiplication and division, practice is key. Here are some fun ways to practice:</p>
                        <ul>
                            <li><strong>Multiplication</strong>: Use objects like toys or fruits to make groups and count how many you have in total. 
                            For example, make 5 groups of 2 toys and count them all together.</li>
                            <li><strong>Division</strong>: Use snacks or coins to share among friends or family members. See how many each person gets 
                            when you divide them equally.</li>
                        </ul>
                        
                        <h3>Fun Tips to Remember</h3>
                        <ul>
                            <li>Multiplication is like adding the same number over and over. For example, 4 × 3 is the same as 4 + 4 + 4.</li>
                            <li>Division is like sharing equally. For example, 15 ÷ 5 means splitting 15 items into 5 equal parts.</li>
                            <li>Practice with real objects to make learning fun and easy.</li>
                        </ul>
                        <p>By understanding and practicing these simple concepts, you'll become a multiplication and division superstar in no time! 
                            So go ahead, try some multiplication and division with your toys or snacks, and see how fun and easy math can be. Happy learning!</p>
                        <p>
                            <em>This article was created in conjunction with the generative AI, ChatGPT.</em>
                        </p>
                    </div>
                    <div class="article" id="article3"> <!-- onclick="location.href='article3.html';" -->
                        <h2>How Covalent Bonds Work: A Simple Guide</h2>
                        <p><em>By Jane Doe and ChatGPT</em> 05/05/2024</p>
                        <p>Have you ever wondered what keeps the atoms in a molecule together? The answer is something called a covalent bond. 
                        Covalent bonds are a fundamental concept in chemistry, and they play a crucial role in the structure of countless substances 
                        around us. Let's break down how covalent bonds work in a way that's easy to understand.</p>

                        <h3>What is a Covalent Bond?</h3>
                        <p>At the most basic level, a covalent bond is a way for atoms to stick together by sharing electrons. Atoms are made up of a 
                        nucleus surrounded by electrons, which move around in regions called electron shells. Atoms are happiest when their outer electron 
                        shell is full. For many atoms, this means having eight electrons in their outer shell, a rule known as the "octet rule."</p>
                        <p>When two atoms come close to each other, they can share electrons to help each other fill up their outer shells. This sharing 
                            of electrons creates a covalent bond, holding the atoms together.</p>

                        <h3>How Does It Work?</h3>
                        <p>Imagine you and a friend each have a piece of candy, but you both want two pieces. If you share your candies with each other, 
                        now you both have what you want. Atoms do something similar with their electrons. Let's look at an example using two hydrogen atoms.</p>
                        <p>Each hydrogen atom has one electron, but they want two to fill their outer shell. When they come close together, they can share their electrons:</p>
                        <ul>
                            <li>Each hydrogen atom shares its one electron with the other.</li>
                            <li>Now, both hydrogen atoms feel like they have two electrons because they are sharing.</li>
                        </ul>
                        <p>This sharing forms a covalent bond, making a molecule of hydrogen (H2).</p>

                        <h3>Types of Covalent Bonds</h3>
                        <p>Covalent bonds can vary depending on how many pairs of electrons are shared:</p>
                        <ul>
                            <li><strong>Single Bond</strong>: When one pair of electrons is shared between two atoms (e.g., H2 or H-H).</li>
                            <li><strong>Double Bond</strong>: When two pairs of electrons are shared (e.g., O2 or O=O).</li>
                            <li><strong>Triple Bond</strong>: When three pairs of electrons are shared (e.g., N2 or N≡N).</li>
                        </ul>
                        <p>The more electrons that are shared, the stronger the bond.</p>

                        <h3>Covalent Bonds in Everyday Life</h3>
                        <p>Covalent bonds are everywhere! They hold together the molecules that make up the air we breathe, the water we drink, and the food
                        we eat. For instance:</p>
                        <ul>
                            <li><strong>Water (H2O)</strong>: Each hydrogen atom shares an electron with the oxygen atom, forming two single covalent bonds.</li>
                            <li><strong>Carbon Dioxide (CO2)</strong>: Each oxygen atom shares two electrons with the carbon atom, forming two double covalent bonds.</li>
                        </ul>

                        <h3>Why Are Covalent Bonds Important?</h3>
                        <p>Covalent bonds are essential because they allow the formation of stable molecules that make up the substances in our world. Without 
                        covalent bonds, we wouldn't have water, plants, animals, or even us!</p>
                        <p>In summary, covalent bonds are like a friendly handshake between atoms, where they share electrons to make each other happy. 
                        This sharing creates a strong connection, forming the molecules that are the building blocks of everything around us. Understanding 
                        covalent bonds helps us appreciate the incredible way nature builds and holds together the materials that make up our world.</p>

                        <p>
                            <em>This article was created in conjunction with the generative AI, ChatGPT.</em>
                        </p>
                    </div>
                    <div class="article" id="article4"> <!-- onclick="location.href='article4.html';" -->
                        <h2>Exploring the World's Biomes</h2>
                        <p><em>By Hans Gruber and ChatGPT</em> 05/05/2024</p>
                        <p>Welcome to the amazing world of biomes! Biomes are large regions of the Earth that share similar climate, plants, and animals. 
                        Each biome is like a unique neighborhood with its own special features. Let's take a journey to explore the different biomes and what 
                           makes each one so special.</p>

                        <h3>What is a Biome?</h3>
                        <p>A biome is a big area of land that has a certain climate and specific types of plants and animals. Climate includes things like 
                        temperature, rainfall, and seasonal changes. The plants and animals in a biome are adapted to live in that environment. Think of a biome 
                        as a huge ecosystem where everything is connected.</p>

                        <h3>Types of Biomes</h3>
                        <p>There are several major types of biomes on Earth. Here are some of the most important ones:</p>
                        <ul>
                            <li><strong>Tundra</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Found in the Arctic and on high mountain tops.</li>
                                    <li><strong>Climate</strong>: Very cold, with long winters and short summers.</li>
                                    <li><strong>Plants and Animals</strong>: Few trees, mostly mosses, lichens, and small shrubs. Animals like caribou, arctic 
                                    foxes, and snowy owls.</li>
                                </ul>
                            </li>
                            <li><strong>Taiga (Boreal Forest)</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Just south of the tundra, in places like Canada and Russia.</li>
                                    <li><strong>Climate</strong>: Cold winters and mild summers.</li>
                                    <li><strong>Plants and Animals</strong>: Evergreen trees like pines, firs, and spruces. Animals include moose, bears, and wolves.</li>
                                </ul>
                            </li>
                            <li><strong>Temperate Forest</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Found in regions like the eastern United States, Europe, and parts of China.</li>
                                    <li><strong>Climate</strong>: Four distinct seasons: winter, spring, summer, and fall.</li>
                                    <li><strong>Plants and Animals</strong>: Deciduous trees (trees that lose their leaves in winter) like oaks and maples. 
                                    Animals such as deer, foxes, and squirrels.</li>
                                </ul>
                            </li>
                            <li><strong>Grassland (Prairie or Savannah)</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Found in the interiors of continents, such as the American Midwest and parts of Africa.</li>
                                    <li><strong>Climate</strong>: Moderate rainfall, with hot summers and cold winters (in prairies) or warm temperatures year-round 
                                    (in savannahs).</li>
                                    <li><strong>Plants and Animals</strong>: Grasses and few trees. Animals like bison, zebras, lions, and elephants.</li>
                                </ul>
                            </li>
                            <li><strong>Desert</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Found in regions like the Sahara in Africa and the southwestern United States.</li>
                                    <li><strong>Climate</strong>: Very dry with extreme temperature changes between day and night.</li>
                                    <li><strong>Plants and Animals</strong>: Cacti and other drought-resistant plants. Animals include camels, lizards, and scorpions.</li>
                                </ul>
                            </li>
                            <li><strong>Tropical Rainforest</strong>:
                                <ul>
                                    <li><strong>Location</strong>: Near the equator, in places like the Amazon Basin and parts of Southeast Asia.</li>
                                    <li><strong>Climate</strong>: Hot and wet year-round.</li>
                                    <li><strong>Plants and Animals</strong>: Tall trees with dense canopies, vines, and a huge variety of plants. Animals such as monkeys, 
                                    parrots, and jaguars.</li>
                                </ul>
                            </li>
                        </ul>

                        <h3>Why are Biomes Important?</h3>
                        <p>Biomes are important because they help support life on Earth. Each biome provides a home for many different plants and animals, which are 
                        adapted to survive in that particular environment. Biomes also play a crucial role in regulating the Earth's climate and providing resources like 
                        food, water, and oxygen.</p>

                        <h3>How Can We Protect Biomes?</h3>
                        <p>Protecting biomes is essential for maintaining the planet's health and biodiversity. Here are some ways we can help:</p>
                        <ul>
                            <li><strong>Reduce, Reuse, Recycle</strong>: Minimizing waste helps reduce pollution and the destruction of natural habitats.</li>
                            <li><strong>Conserve Water and Energy</strong>: Using resources wisely helps protect biomes from overuse.</li>
                            <li><strong>Support Conservation Efforts</strong>: Helping organizations that work to preserve natural areas and wildlife.</li>
                            <li><strong>Learn and Educate</strong>: Understanding more about biomes and sharing that knowledge encourages others to care about the environment.</li>
                        </ul>

                        <p>In conclusion, biomes are fascinating and vital parts of our planet. Each one has its own unique characteristics and supports a diverse range of life. 
                        By learning about and protecting biomes, we can help ensure that these incredible ecosystems continue to thrive for future generations to enjoy.</p>

                        <p>
                            <em>This article was created in conjunction with the generative AI, ChatGPT.</em>
                        </p>
                    </div>
                    <div class="article" id="article5"> <!-- onclick="location.href='article5.html';" -->
                        <h2>Article #5</h2>
                        <p><em>By Willy Wonka and ChatGPT</em> 05/05/2024</p>
                        <p>Writing a short story is an exciting and creative way to express your ideas, emotions, and imagination. Whether you're a seasoned writer or 
                        just starting out, crafting a short story can be a rewarding experience. Here's a simple guide to help you create your own short story from start 
                        to finish.</p>

                        <h3>1. Start with an Idea</h3>
                        <p>Every great story begins with an idea. Your idea can come from anywhere: a personal experience, a dream, a piece of news, or even a simple 
                        "what if" question. Think about something that excites or intrigues you, as this will keep you motivated throughout the writing process.</p>

                        <h3>2. Develop Your Characters</h3>
                        <p>Characters are the heart of any story. Take some time to think about who your main characters are. Consider their backgrounds, personalities, 
                        desires, and fears. A well-developed character is more believable and relatable. Ask yourself questions like:</p>
                        <ul>
                            <li>What does this character want?</li>
                            <li>What is their biggest fear?</li>
                            <li>How do they react to different situations?</li>
                        </ul>

                        <h3>3. Create a Setting</h3>
                        <p>The setting is where your story takes place. It can be anywhere: a bustling city, a quiet village, a magical kingdom, or even outer space. 
                        Describe the setting in a way that helps readers visualize it. Think about the time of day, the weather, and the overall atmosphere. A vivid 
                        setting can enhance your story and make it more immersive.</p>

                        <h3>4. Outline Your Plot</h3>
                        <p>The plot is the sequence of events that make up your story. A simple structure to follow is:</p>
                        <ol>
                            <li><strong>Beginning</strong>: Introduce your characters and setting. Set up the main conflict or problem.</li>
                            <li><strong>Middle</strong>: Develop the conflict. Show your characters trying to overcome obstacles.</li>
                            <li><strong>End</strong>: Resolve the conflict. Show how the characters have changed or what they have learned.</li>
                        </ol>
                        <p>Creating an outline helps you stay organized and ensures your story has a clear direction.</p>

                        <h3>5. Write the First Draft</h3>
                        <p>Now that you have your idea, characters, setting, and plot, it's time to start writing. Don't worry about making it perfect; just focus 
                        on getting your story down on paper. Let your creativity flow and don't be afraid to take risks. Write freely and allow your characters to 
                        come to life.</p>

                        <h3>6. Revise and Edit</h3>
                        <p>Once you've finished your first draft, take a break before revising. This will help you return to your story with fresh eyes. 
                        During revision, look for:</p>
                        <ul>
                            <li><strong>Clarity</strong>: Are there any confusing parts?</li>
                            <li><strong>Consistency</strong>: Do your characters act in believable ways? Is the setting consistent?</li>
                            <li><strong>Pacing</strong>: Does the story move at a good pace? Are there any parts that drag or feel rushed?</li>
                        </ul>
                        <p>After revising, edit for grammar, spelling, and punctuation. This step is crucial for polishing your story and making it enjoyable to read.</p>

                        <h3>7. Get Feedback</h3>
                        <p>Sharing your story with others can provide valuable insights. Ask friends, family, or writing groups to read your story and give feedback. 
                        Be open to constructive criticism and use it to improve your work.</p>

                        <h3>8. Finalize Your Story</h3>
                        <p>After incorporating feedback and making final adjustments, your story is ready. Give it a final read-through to catch any last-minute errors. 
                        Once you're satisfied, consider where you'd like to share it. You can submit it to a magazine, enter it into a contest, or share it online.</p>

                        <h3>Tips for Success</h3>
                        <ul>
                            <li><strong>Keep it Concise</strong>: A short story should focus on one main idea or conflict. Avoid unnecessary subplots or characters.</li>
                            <li><strong>Show, Don't Tell</strong>: Use descriptive language and actions to show what's happening, rather than just telling the reader.</li>
                            <li><strong>Create Tension</strong>: Keep readers engaged by creating suspense or uncertainty.</li>
                            <li><strong>End with Impact</strong>: Aim for a memorable ending that leaves a lasting impression.</li>
                        </ul>

                        <p>Writing a short story is a wonderful way to express your creativity and share your voice with the world. By following these steps and tips, you'll 
                        be well on your way to crafting a compelling and engaging story. Happy writing!</p>

                        <p>
                            <em>This article was created in conjunction with the generative AI, ChatGPT.</em>
                        </p>
                    </div>
                    <!-- <div class="article" onclick="location.href='article6.html';">
                        <h2>Article #6</h2>
                        <p><em>By Jane Doe</em> 05/05/2024</p>
                        <p>Sed enim ut sem viverra. Egestas quis ipsum suspendisse ultrices...</p>
                    </div> -->
                </div>
            </div>

        <div class="sidebar">
            <div class="filter">
                <h3>Filter by Keyword</h3>
                <input type="text" id="search" name="search" placeholder="Search...">
                <button type="submit" onclick="search()">Search</button>
            </div>
            <div class="filter">
                <h3>Filter by Subject</h3>
                <select id="subject" name="subject">
                    <option value="math">Math</option>
                    <option value="science">Science</option>
                    <option value="english">English</option>
                    <option value="geography">Geography</option>
                </select>
                <button type="submit" onclick="filter()">Filter</button>
            </div>
        </div>
    </main>

    <div class="cookie-consent-overlay" id="cookieConsent">
        <div class="cookie-consent-box">
            <p>We use cookies to ensure you get the best experience on our website. <a href="../policy/policy.php" target="_blank">Learn more</a></p>
            <button class="cookie-accept-btn">Accept</button>
            <button class="cookie-decline-btn">Decline</button>
        </div>
    </div>  

    <footer>
        <div class="sec-links">
            <div class="tutorfy">
                <h4>Tutorfy</h4>
                <a href="../homepage/homepage.php" class="sec-nav">Home</a>
                <a href="../article/article.php" class="sec-nav">Articles</a>
                <a href="../store/store.php" class="sec-nav">Store</a>
                <a href="../forum/forum.php" class="sec-nav">Forums</a>
            </div>

            <div class="about">
                <h4>About</h4>
                <a href="../policy/policy.php" class="sec-nav">Cookie and Privacy Policy</a>
                <a href="../contact/contact.php" class="sec-nav">Contact us</a>
            </div>

            <div class="account">
                <h4>Account</h4>
                <a href="../login/login.php" class="`sec-nav">Login</a>
                <a href="../cart/cart.php" class="sec-nav">Cart</a>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2023</h4>
    </footer>
</body>
</html>
