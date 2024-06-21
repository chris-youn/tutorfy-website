document.addEventListener("DOMContentLoaded", function() {
    fetch("https://opentdb.com/api.php?amount=10")
        .then(response => response.json())
        .then(data => {
            if (data.response_code === 0) {
                const quizContainer = document.getElementById('quiz-container');
                data.results.forEach(question => {
                    const questionElement = document.createElement("div");
                    questionElement.classList.add("quiz-question");
                    questionElement.innerHTML = `<h3>${question.question}</h3>`;
                    
                    const answersList = document.createElement("ul");

                    // Combine correct and incorrect answers and shuffle them
                    const answers = [...question.incorrect_answers, question.correct_answer];
                    answers.sort(() => Math.random() - 0.5);

                    answers.forEach(answer => {
                        const answerElement = document.createElement("li");
                        answerElement.textContent = answer;
                        answersList.appendChild(answerElement);

                        // Add click event to show correct or incorrect
                        answerElement.addEventListener('click', () => {
                            if (answer === question.correct_answer) {
                                answerElement.style.backgroundColor = 'lightgreen';
                            } else {
                                answerElement.style.backgroundColor = 'lightcoral';
                            }
                        });
                    });

                    questionElement.appendChild(answersList);
                    quizContainer.appendChild(questionElement);
                });
            } else {
                document.getElementById('quiz-container').textContent = "Failed to fetch trivia questions.";
            }
        })
        .catch(error => console.error("Error:", error));
});
