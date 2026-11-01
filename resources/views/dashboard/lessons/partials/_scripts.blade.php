@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    let questions = [];
    let currentQuestionId = null;

    let currentPage = 1;
    const QUESTIONS_PER_PAGE = 5;

    /*
    |--------------------------------------------------------------------------
    | Lesson Type
    |--------------------------------------------------------------------------
    */

    window.toggleLessonTypeFields = function () {

        const typeSelect = document.getElementById('type');

        if (!typeSelect) return;

        const type = typeSelect.value;

        /*
         * Hide normal lesson type blocks.
         */
        document.querySelectorAll('.type-field').forEach(function (el) {

            el.style.display = 'none';

            el.querySelectorAll('textarea[name="content"]').forEach(function (ta) {
                ta.disabled = true;
            });

        });

        /*
         * Quiz uses the new custom wrapper.
         */
        const quizWrapper = document.getElementById('quiz-fields-wrapper');

        if (quizWrapper) {
            quizWrapper.style.display = type === 'quiz' ? 'block' : 'none';
        }

        /*
         * Other lesson types still use the old system.
         */
        const activeBlock = document.querySelector(`.field-${type}`);

        if (activeBlock && type !== 'quiz') {

            activeBlock.style.display = 'block';

            activeBlock.querySelectorAll('textarea[name="content"]').forEach(function (ta) {
                ta.disabled = false;
            });
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    function getValue(value) {

        if (value === null || value === undefined) {
            return '';
        }

        if (typeof value === 'string' || typeof value === 'number') {
            return String(value);
        }

        if (typeof value === 'object') {

            return String(
                value.option_text ??
                value.question_text ??
                value.text ??
                value.title ??
                value.feedback ??
                ''
            );
        }

        return '';
    }


    function generateQuestionId() {

        return 'new_' + Date.now() + '_' + Math.random()
            .toString(36)
            .substring(2, 9);
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Question
    |--------------------------------------------------------------------------
    */

    function normalizeQuestion(data = {}) {

        let options = data.options || [];

        if (!Array.isArray(options)) {
            options = Object.values(options);
        }

        let feedbacks = data.option_feedbacks || data.feedback || [];

        if (!Array.isArray(feedbacks)) {
            feedbacks = Object.values(feedbacks);
        }

        const normalizedOptions = options.map(function (option, index) {

            if (typeof option === 'object' && option !== null) {

                return {
                    text: getValue(
                        option.option_text ??
                        option.text ??
                        ''
                    ),

                    feedback: getValue(
                        option.feedback ??
                        feedbacks[index] ??
                        ''
                    ),

                    is_correct:
                        option.is_correct == 1 ||
                        option.is_correct === true
                };
            }

            return {
                text: getValue(option),
                feedback: getValue(feedbacks[index] ?? ''),
                is_correct: false
            };
        });


        let correctOption = data.correct_option;

        if (
            correctOption === undefined ||
            correctOption === null ||
            correctOption === ''
        ) {
            correctOption = normalizedOptions.findIndex(
                option => option.is_correct
            );
        }

        correctOption = parseInt(correctOption);

        if (isNaN(correctOption)) {
            correctOption = 0;
        }


        let isCorrect = data.is_correct;

        if (isCorrect === undefined || isCorrect === null) {
            isCorrect = 1;
        }


        return {

            id: data.id ?? null,

            frontend_id:
                data.frontend_id ??
                generateQuestionId(),

            text:
                getValue(
                    data.text ??
                    data.question_text ??
                    ''
                ),

            type:
                data.type ??
                'multiple_choice',

            hint:
                getValue(data.hint ?? ''),

            explanation:
                getValue(data.explanation ?? ''),

            options:
                normalizedOptions,

            correct_option:
                correctOption,

            is_correct:
                String(isCorrect) === '1'
                    ? 1
                    : 0
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Load Questions
    |--------------------------------------------------------------------------
    */

    const oldQuestions = @json(old('questions', null));
    const dbQuestions = @json($lesson->questions ?? []);


    let initialQuestions =
        oldQuestions !== null
            ? oldQuestions
            : dbQuestions;


    if (!Array.isArray(initialQuestions)) {
        initialQuestions = Object.values(initialQuestions || {});
    }


    questions = initialQuestions.map(function (question) {
        return normalizeQuestion(question);
    });


    /*
    |--------------------------------------------------------------------------
    | Ensure New MC Question Has Options
    |--------------------------------------------------------------------------
    */

    function ensureOptions(question) {

        if (question.type !== 'multiple_choice') {
            return;
        }

        if (!Array.isArray(question.options)) {
            question.options = [];
        }

        if (question.options.length === 0) {

            question.options.push({
                text: '',
                feedback: '',
                is_correct: true
            });

            question.options.push({
                text: '',
                feedback: '',
                is_correct: false
            });

            question.correct_option = 0;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Current Question
    |--------------------------------------------------------------------------
    */

    function getCurrentQuestion() {

        return questions.find(function (question) {
            return question.frontend_id === currentQuestionId;
        }) || null;
    }


    /*
    |--------------------------------------------------------------------------
    | Filtered Questions
    |--------------------------------------------------------------------------
    */

    function getFilteredQuestions() {

        const searchInput =
            document.getElementById('question-search');

        const filterInput =
            document.getElementById('question-filter');


        const search =
            searchInput
                ? searchInput.value.trim().toLowerCase()
                : '';


        const filter =
            filterInput
                ? filterInput.value
                : 'all';


        return questions.filter(function (question) {

            const matchesSearch =
                !search ||
                question.text.toLowerCase().includes(search);


            const matchesFilter =
                filter === 'all' ||
                question.type === filter;


            return matchesSearch && matchesFilter;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Render Question List
    |--------------------------------------------------------------------------
    */

    function renderQuestionList() {

        const list =
            document.getElementById('question-list');

        if (!list) return;


        const filteredQuestions =
            getFilteredQuestions();


        const total =
            filteredQuestions.length;


        const totalPages =
            Math.max(
                1,
                Math.ceil(total / QUESTIONS_PER_PAGE)
            );


        if (currentPage > totalPages) {
            currentPage = totalPages;
        }


        const start =
            (currentPage - 1) *
            QUESTIONS_PER_PAGE;


        const pageQuestions =
            filteredQuestions.slice(
                start,
                start + QUESTIONS_PER_PAGE
            );


        list.innerHTML = '';


        /*
         * Total count
         */
        const countElement =
            document.getElementById('question-total-count');

        if (countElement) {
            countElement.textContent = questions.length;
        }


        /*
         * Empty
         */
        if (pageQuestions.length === 0) {

            list.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fa-solid fa-circle-question fa-2x mb-2 opacity-50"></i>
                    <div>No questions found.</div>
                </div>
            `;

            renderPagination(totalPages, total);
            return;
        }


        /*
         * List items
         */
        pageQuestions.forEach(function (question) {

            const originalIndex =
                questions.indexOf(question);


            const item =
                document.createElement('div');

            item.className =
                'question-list-item' +
                (
                    question.frontend_id === currentQuestionId
                        ? ' active'
                        : ''
                );


            const questionNumber =
                originalIndex + 1;


            const questionText =
                question.text.trim()
                    ? question.text
                    : 'New Question';


            const typeText =
                question.type === 'boolean'
                    ? 'True / False'
                    : 'Multiple Choice';


            item.innerHTML = `
                <div class="question-number">
                    ${questionNumber}
                </div>

                <div class="question-list-content">

                    <div class="question-list-text">
                        ${escapeHtml(questionText)}
                    </div>

                    <div class="question-list-type">
                        ${typeText}
                    </div>

                </div>
            `;


            item.addEventListener('click', function () {

                selectQuestion(question.frontend_id);

            });


            list.appendChild(item);
        });


        /*
         * Page information
         */
        const pageInfo =
            document.getElementById('question-page-info');

        if (pageInfo) {

            if (total === 0) {

                pageInfo.textContent =
                    '0 questions';

            } else {

                const from =
                    start + 1;

                const to =
                    Math.min(
                        start + QUESTIONS_PER_PAGE,
                        total
                    );

                pageInfo.textContent =
                    `${from}-${to} of ${total}`;
            }
        }


        renderPagination(totalPages, total);
    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    function renderPagination(totalPages, total) {

        const pagination =
            document.getElementById('question-pagination');

        if (!pagination) return;


        pagination.innerHTML = '';


        if (totalPages <= 1) {
            return;
        }


        /*
         * Previous
         */
        const previous =
            document.createElement('button');

        previous.type = 'button';
        previous.className = 'question-page-btn';
        previous.textContent = '‹';
        previous.disabled = currentPage === 1;


        previous.addEventListener('click', function () {

            if (currentPage > 1) {

                currentPage--;

                renderQuestionList();
            }
        });


        pagination.appendChild(previous);


        /*
         * Pages
         */
        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'question-page-btn' +
                (
                    page === currentPage
                        ? ' active'
                        : ''
                );

            button.textContent = page;


            button.addEventListener('click', function () {

                currentPage = page;

                renderQuestionList();
            });


            pagination.appendChild(button);
        }


        /*
         * Next
         */
        const next =
            document.createElement('button');

        next.type = 'button';
        next.className = 'question-page-btn';
        next.textContent = '›';
        next.disabled = currentPage === totalPages;


        next.addEventListener('click', function () {

            if (currentPage < totalPages) {

                currentPage++;

                renderQuestionList();
            }
        });


        pagination.appendChild(next);
    }


    /*
    |--------------------------------------------------------------------------
    | Select Question
    |--------------------------------------------------------------------------
    */

    function selectQuestion(frontendId) {

        const question =
            questions.find(function (item) {
                return item.frontend_id === frontendId;
            });


        if (!question) return;


        /*
         * Save whatever is currently in editor first.
         */
        if (currentQuestionId) {
            saveEditorToQuestion();
        }


        currentQuestionId =
            frontendId;


        ensureOptions(question);

        renderQuestionEditor();

        renderQuestionList();

        updateNavigationButtons();
    }


    /*
    |--------------------------------------------------------------------------
    | Render Editor
    |--------------------------------------------------------------------------
    */

    function renderQuestionEditor() {

        const question =
            getCurrentQuestion();


        const empty =
            document.getElementById(
                'question-editor-empty'
            );

        const editor =
            document.getElementById(
                'question-editor'
            );


        if (!question) {

            if (empty) {
                empty.style.display = 'flex';
            }

            if (editor) {
                editor.style.display = 'none';
            }

            return;
        }


        if (empty) {
            empty.style.display = 'none';
        }

        if (editor) {
            editor.style.display = 'block';
        }


        /*
         * Question number
         */
        const number =
            questions.indexOf(question) + 1;


        const numberElement =
            document.getElementById(
                'question-editor-number'
            );


        if (numberElement) {

            numberElement.textContent =
                `Question ${number}`;
        }


        /*
         * Type badge
         */
        const typeBadge =
            document.getElementById(
                'question-editor-type-badge'
            );


        if (typeBadge) {

            typeBadge.textContent =
                question.type === 'boolean'
                    ? 'True / False'
                    : 'Multiple Choice';
        }


        /*
         * Text
         */
        const text =
            document.getElementById(
                'editor-question-text'
            );


        if (text) {
            text.value = question.text;
        }


        /*
         * Type
         */
        const type =
            document.getElementById(
                'editor-question-type'
            );


        if (type) {
            type.value = question.type;
        }


        /*
         * Hint
         */
        const hint =
            document.getElementById(
                'editor-hint'
            );


        if (hint) {
            hint.value = question.hint;
        }


        /*
         * Explanation
         */
        const explanation =
            document.getElementById(
                'editor-explanation'
            );


        if (explanation) {
            explanation.value = question.explanation;
        }


        /*
         * Type-specific editor
         */
        renderQuestionTypeEditor(question);


        /*
         * Save status
         */
        setSaveStatus('Editing');
    }


    /*
    |--------------------------------------------------------------------------
    | Render MC / Boolean
    |--------------------------------------------------------------------------
    */

    function renderQuestionTypeEditor(question) {

    const mcEditor =
        document.getElementById(
            'multiple-choice-editor'
        );

    const booleanEditor =
        document.getElementById(
            'boolean-editor'
        );

    if (question.type === 'boolean') {

        if (mcEditor) {
            mcEditor.style.display = 'none';
        }

        if (booleanEditor) {
            booleanEditor.style.display = 'block';
        }

        /*
         * IMPORTANT:
         * Laravel/database Boolean may arrive as:
         * true, false, 1, 0, "1", "0"
         */
        const isCorrect =
            question.is_correct === true ||
            question.is_correct === 1 ||
            question.is_correct === '1';

        console.log(
            'Boolean is_correct:',
            question.is_correct,
            '=>',
            isCorrect
        );

        /*
         * Clear both radios.
         */
        document
            .querySelectorAll(
                'input[name="editor_boolean_answer"]'
            )
            .forEach(function (input) {
                input.checked = false;
            });

        /*
         * Select True or False.
         */
        const radio = document.querySelector(
            `input[name="editor_boolean_answer"][value="${isCorrect ? '1' : '0'}"]`
        );

        if (radio) {
            radio.checked = true;
        }

        /*
         * Load True / False feedback.
         */
        const trueFeedback =
            document.getElementById(
                'editor-true-feedback'
            );

        const falseFeedback =
            document.getElementById(
                'editor-false-feedback'
            );

        const trueOption =
            (question.options || []).find(function (option) {
                return String(option.text).toLowerCase() === 'true';
            });

        const falseOption =
            (question.options || []).find(function (option) {
                return String(option.text).toLowerCase() === 'false';
            });

        if (trueFeedback) {
            trueFeedback.value =
                trueOption?.feedback || '';
        }

        if (falseFeedback) {
            falseFeedback.value =
                falseOption?.feedback || '';
        }

        return;
    }

    /*
     * Multiple choice
     */
    if (mcEditor) {
        mcEditor.style.display = 'block';
    }

    if (booleanEditor) {
        booleanEditor.style.display = 'none';
    }

    ensureOptions(question);

    renderOptions(question);
}


    /*
    |--------------------------------------------------------------------------
    | Render Options
    |--------------------------------------------------------------------------
    */

    function renderOptions(question) {

        const container =
            document.getElementById(
                'editor-options'
            );

        if (!container) return;


        container.innerHTML = '';


        question.options.forEach(function (option, index) {

            const row =
                document.createElement('div');

            row.className = 'option-row';

            row.dataset.index = index;


            row.innerHTML = `
                <div class="option-main">

                    <input
                        type="radio"
                        class="form-check-input option-correct"
                        name="editor_correct_option"
                        value="${index}"
                        ${question.correct_option === index ? 'checked' : ''}
                        title="Correct answer"
                    >

                    <div class="option-text-wrapper">

                        <input
                            type="text"
                            class="form-control option-text"
                            value="${escapeHtml(option.text)}"
                            placeholder="Option ${index + 1}"
                        >

                        <input
                            type="text"
                            class="form-control form-control-sm option-feedback"
                            value="${escapeHtml(option.feedback)}"
                            placeholder="Feedback for this answer (optional)"
                        >

                    </div>

                    <button
                        type="button"
                        class="remove-option"
                        title="Remove option"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>

                </div>
            `;


            /*
             * Correct answer
             */
            const radio =
                row.querySelector(
                    '.option-correct'
                );


            radio.addEventListener('change', function () {

                question.correct_option =
                    parseInt(this.value);

                question.options.forEach(
                    function (item, i) {

                        item.is_correct =
                            i === question.correct_option;
                    }
                );

                setSaveStatus('Unsaved changes');
            });


            /*
             * Text
             */
            const textInput =
                row.querySelector('.option-text');


            textInput.addEventListener(
                'input',
                function () {

                    question.options[index].text =
                        this.value;

                    setSaveStatus('Unsaved changes');

                    renderQuestionList();
                }
            );


            /*
             * Feedback
             */
            const feedbackInput =
                row.querySelector(
                    '.option-feedback'
                );


            feedbackInput.addEventListener(
                'input',
                function () {

                    question.options[index].feedback =
                        this.value;

                    setSaveStatus('Unsaved changes');
                }
            );


            /*
             * Remove
             */
            const removeButton =
                row.querySelector('.remove-option');


            removeButton.addEventListener(
                'click',
                function () {

                    if (question.options.length <= 1) {

                        alert(
                            'A multiple choice question must have at least one option.'
                        );

                        return;
                    }


                    question.options.splice(index, 1);


                    if (
                        question.correct_option >=
                        question.options.length
                    ) {

                        question.correct_option =
                            question.options.length - 1;
                    }


                    question.options.forEach(
                        function (item, i) {

                            item.is_correct =
                                i === question.correct_option;
                        }
                    );


                    renderOptions(question);

                    setSaveStatus('Unsaved changes');
                }
            );


            container.appendChild(row);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Save Editor -> Question Object
    |--------------------------------------------------------------------------
    */

    function saveEditorToQuestion() {

        const question =
            getCurrentQuestion();


        if (!question) return;


        /*
         * Text
         */
        const text =
            document.getElementById(
                'editor-question-text'
            );


        if (text) {
            question.text = text.value;
        }


        /*
         * Type
         */
        const type =
            document.getElementById(
                'editor-question-type'
            );


        if (type) {
            question.type = type.value;
        }


        /*
         * Hint
         */
        const hint =
            document.getElementById(
                'editor-hint'
            );


        if (hint) {
            question.hint = hint.value;
        }


        /*
         * Explanation
         */
        const explanation =
            document.getElementById(
                'editor-explanation'
            );


        if (explanation) {
            question.explanation =
                explanation.value;
        }


        /*
         * Boolean
         */
        if (question.type === 'boolean') {

            const selected =
                document.querySelector(
                    'input[name="editor_boolean_answer"]:checked'
                );


            if (selected) {
                question.is_correct =
                    parseInt(selected.value);
            }

            return;
        }


        /*
         * Multiple Choice
         */
        const optionRows =
            document.querySelectorAll(
                '#editor-options .option-row'
            );


        question.options =
            Array.from(optionRows).map(
                function (row) {

                    return {
                        text:
                            row.querySelector(
                                '.option-text'
                            )?.value || '',

                        feedback:
                            row.querySelector(
                                '.option-feedback'
                            )?.value || '',

                        is_correct:
                            false
                    };
                }
            );


        const selectedCorrect =
            document.querySelector(
                'input[name="editor_correct_option"]:checked'
            );


        question.correct_option =
            selectedCorrect
                ? parseInt(selectedCorrect.value)
                : 0;


        question.options.forEach(
            function (option, index) {

                option.is_correct =
                    index === question.correct_option;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Save Status
    |--------------------------------------------------------------------------
    */

    function setSaveStatus(text) {

        const element =
            document.getElementById(
                'question-save-status'
            );

        if (!element) return;

        element.textContent = text;
    }


    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    function updateNavigationButtons() {

        const question =
            getCurrentQuestion();

        if (!question) return;


        const index =
            questions.indexOf(question);


        const previous =
            document.getElementById(
                'previous-question-btn'
            );


        const next =
            document.getElementById(
                'next-question-btn'
            );


        if (previous) {
            previous.disabled =
                index <= 0;
        }


        if (next) {
            next.disabled =
                index >= questions.length - 1;
        }
    }


    function goToPreviousQuestion() {

        saveEditorToQuestion();


        const question =
            getCurrentQuestion();

        if (!question) return;


        const index =
            questions.indexOf(question);


        if (index > 0) {

            selectQuestion(
                questions[index - 1].frontend_id
            );
        }
    }


    function goToNextQuestion() {

        saveEditorToQuestion();


        const question =
            getCurrentQuestion();

        if (!question) return;


        const index =
            questions.indexOf(question);


        if (
            index >= 0 &&
            index < questions.length - 1
        ) {

            selectQuestion(
                questions[index + 1].frontend_id
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Add Question
    |--------------------------------------------------------------------------
    */

    function addNewQuestion() {

        /*
         * Save current editor first.
         */
        if (currentQuestionId) {
            saveEditorToQuestion();
        }


        const question =
            normalizeQuestion({

                id: null,

                text: '',

                type: 'multiple_choice',

                hint: '',

                explanation: '',

                options: [
                    {
                        text: '',
                        feedback: '',
                        is_correct: true
                    },
                    {
                        text: '',
                        feedback: '',
                        is_correct: false
                    }
                ],

                correct_option: 0
            });


        questions.push(question);


        currentQuestionId =
            question.frontend_id;


        /*
         * Open last question page.
         */
        const totalPages =
            Math.ceil(
                questions.length /
                QUESTIONS_PER_PAGE
            );


        currentPage =
            totalPages;


        renderQuestionList();

        renderQuestionEditor();

        updateNavigationButtons();


        /*
         * Focus question.
         */
        setTimeout(function () {

            const text =
                document.getElementById(
                    'editor-question-text'
                );

            if (text) {
                text.focus();
            }

        }, 100);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Question
    |--------------------------------------------------------------------------
    */

    function deleteCurrentQuestion() {

        const question =
            getCurrentQuestion();

        if (!question) return;


        const number =
            questions.indexOf(question) + 1;


        if (
            !confirm(
                `Delete Question ${number}?`
            )
        ) {
            return;
        }


        const index =
            questions.indexOf(question);


        questions.splice(index, 1);


        currentQuestionId = null;


        if (questions.length === 0) {

            renderQuestionList();
            renderQuestionEditor();
            updateNavigationButtons();

            return;
        }


        /*
         * Select a nearby question.
         */
        const nextQuestion =
            questions[index] ||
            questions[index - 1];


        currentQuestionId =
            nextQuestion.frontend_id;


        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    getFilteredQuestions().length /
                    QUESTIONS_PER_PAGE
                )
            );


        if (currentPage > totalPages) {
            currentPage = totalPages;
        }


        renderQuestionList();

        renderQuestionEditor();

        updateNavigationButtons();
    }


    /*
    |--------------------------------------------------------------------------
    | Add Option
    |--------------------------------------------------------------------------
    */

    function addOption() {

        const question =
            getCurrentQuestion();

        if (!question) return;


        if (question.type !== 'multiple_choice') {
            return;
        }


        saveEditorToQuestion();


        question.options.push({

            text: '',

            feedback: '',

            is_correct: false
        });


        renderOptions(question);

        setSaveStatus('Unsaved changes');
    }


    /*
    |--------------------------------------------------------------------------
    | Question Type Change
    |--------------------------------------------------------------------------
    */

    function changeQuestionType() {

        const question =
            getCurrentQuestion();

        if (!question) return;


        saveEditorToQuestion();


        const select =
            document.getElementById(
                'editor-question-type'
            );


        if (!select) return;


        question.type =
            select.value;


        if (question.type === 'multiple_choice') {

            ensureOptions(question);

        } else {

            /*
             * Keep MC options in memory, but they won't
             * be submitted for Boolean.
             */
            if (
                question.is_correct !== 0 &&
                question.is_correct !== 1
            ) {
                question.is_correct = 1;
            }
        }


        renderQuestionTypeEditor(question);

        renderQuestionList();

        setSaveStatus('Unsaved changes');
    }


    /*
    |--------------------------------------------------------------------------
    | Local Save
    |--------------------------------------------------------------------------
    */

    function saveCurrentQuestion() {

        saveEditorToQuestion();

        renderQuestionList();

        renderQuestionEditor();

        updateNavigationButtons();

        setSaveStatus('Saved locally');
    }


    /*
    |--------------------------------------------------------------------------
    | Hidden Form Inputs
    |--------------------------------------------------------------------------
    |
    | This converts our JavaScript question array back into the
    | exact structure your current LessonController::update()
    | expects.
    |
    */

    function buildHiddenQuestionInputs() {

        const container =
            document.getElementById(
                'questions-container'
            );

        if (!container) return;


        /*
         * Save current editor.
         */
        if (currentQuestionId) {
            saveEditorToQuestion();
        }


        container.innerHTML = '';


        questions.forEach(
            function (question, questionIndex) {

                /*
                 * Question ID
                 */
                if (question.id !== null && question.id !== undefined) {

                    addHiddenInput(
                        container,
                        `questions[${questionIndex}][id]`,
                        question.id
                    );
                }


                /*
                 * Text
                 */
                addHiddenInput(
                    container,
                    `questions[${questionIndex}][text]`,
                    question.text
                );


                /*
                 * Type
                 */
                addHiddenInput(
                    container,
                    `questions[${questionIndex}][type]`,
                    question.type
                );


                /*
                 * Hint
                 */
                addHiddenInput(
                    container,
                    `questions[${questionIndex}][hint]`,
                    question.hint
                );


                /*
                 * Explanation
                 */
                addHiddenInput(
                    container,
                    `questions[${questionIndex}][explanation]`,
                    question.explanation
                );


                /*
                 * Multiple Choice
                 */
                if (question.type === 'multiple_choice') {

                    question.options.forEach(
                        function (option, optionIndex) {

                            addHiddenInput(
                                container,
                                `questions[${questionIndex}][options][${optionIndex}]`,
                                option.text
                            );


                            addHiddenInput(
                                container,
                                `questions[${questionIndex}][option_feedbacks][${optionIndex}]`,
                                option.feedback
                            );
                        }
                    );


                    addHiddenInput(
                        container,
                        `questions[${questionIndex}][correct_option]`,
                        question.correct_option
                    );

                }


                /*
                 * Boolean
                 */
                if (question.type === 'boolean') {

                    addHiddenInput(
                        container,
                        `questions[${questionIndex}][is_correct]`,
                        question.is_correct
                    );
                }
            }
        );
    }


    function addHiddenInput(
        container,
        name,
        value
    ) {

        const input =
            document.createElement('input');


        input.type = 'hidden';

        input.name = name;

        input.value =
            value === null ||
            value === undefined
                ? ''
                : value;


        container.appendChild(input);
    }


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    const form =
        document.querySelector(
            '#quiz-fields-wrapper'
        )?.closest('form');


    if (form) {

        form.addEventListener(
            'submit',
            function () {

                buildHiddenQuestionInputs();

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    */

    const addQuestionButton =
        document.getElementById(
            'add-question-btn'
        );


    if (addQuestionButton) {

        addQuestionButton.addEventListener(
            'click',
            addNewQuestion
        );
    }


    const emptyAddButton =
        document.getElementById(
            'empty-add-question-btn'
        );


    if (emptyAddButton) {

        emptyAddButton.addEventListener(
            'click',
            addNewQuestion
        );
    }


    const addOptionButton =
        document.getElementById(
            'add-option-btn'
        );


    if (addOptionButton) {

        addOptionButton.addEventListener(
            'click',
            addOption
        );
    }


    const previousButton =
        document.getElementById(
            'previous-question-btn'
        );


    if (previousButton) {

        previousButton.addEventListener(
            'click',
            goToPreviousQuestion
        );
    }


    const nextButton =
        document.getElementById(
            'next-question-btn'
        );


    if (nextButton) {

        nextButton.addEventListener(
            'click',
            goToNextQuestion
        );
    }


    const deleteButton =
        document.getElementById(
            'delete-question-btn'
        );


    if (deleteButton) {

        deleteButton.addEventListener(
            'click',
            deleteCurrentQuestion
        );
    }


    const saveButton =
        document.getElementById(
            'save-question-local-btn'
        );


    if (saveButton) {

        saveButton.addEventListener(
            'click',
            saveCurrentQuestion
        );
    }


    const typeSelector =
        document.getElementById(
            'editor-question-type'
        );


    if (typeSelector) {

        typeSelector.addEventListener(
            'change',
            changeQuestionType
        );
    }


    const searchInput =
        document.getElementById(
            'question-search'
        );


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                currentPage = 1;

                renderQuestionList();
            }
        );
    }


    const filterInput =
        document.getElementById(
            'question-filter'
        );


    if (filterInput) {

        filterInput.addEventListener(
            'change',
            function () {

                currentPage = 1;

                renderQuestionList();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    toggleLessonTypeFields();


    if (questions.length > 0) {

        /*
         * Select first question.
         */
        currentQuestionId =
            questions[0].frontend_id;


        ensureOptions(
            questions[0]
        );


        renderQuestionList();

        renderQuestionEditor();

        updateNavigationButtons();

    } else {

        renderQuestionList();

        renderQuestionEditor();
    }

    /* ==========================================================================
   EXCEL QUESTION IMPORT
   ========================================================================== */

const importQuestionsBtn =
    document.getElementById('import-questions-btn');

const importQuestionsModalElement =
    document.getElementById('importQuestionsModal');

const questionImportFile =
    document.getElementById('question-import-file');

const importLoading =
    document.getElementById('import-loading');

const importError =
    document.getElementById('import-error');

const importPreview =
    document.getElementById('import-preview');

const importPreviewBody =
    document.getElementById('import-preview-body');

const importQuestionCount =
    document.getElementById('import-question-count');

const confirmImportBtn =
    document.getElementById('confirm-import-btn');

const editorTrueFeedback =
    document.getElementById('editor-true-feedback');

const editorFalseFeedback =
    document.getElementById('editor-false-feedback');


/*
|--------------------------------------------------------------------------
| Imported questions waiting for confirmation
|--------------------------------------------------------------------------
*/

let importedQuestions = [];


/*
|--------------------------------------------------------------------------
| Bootstrap modal
|--------------------------------------------------------------------------
*/

let importModal = null;

if (importQuestionsModalElement) {

    /*
     * Move modal directly under <body>.
     * This prevents the parent form/card/theme styles
     * from breaking Bootstrap's backdrop and close button.
     */
    document.body.appendChild(
        importQuestionsModalElement
    );

    importModal = new bootstrap.Modal(
        importQuestionsModalElement,
        {
            backdrop: true,
            keyboard: true,
            focus: true
        }
    );
}


/*
|--------------------------------------------------------------------------
| Open Import Modal
|--------------------------------------------------------------------------
*/

if (importQuestionsBtn) {

    importQuestionsBtn.addEventListener('click', function () {

        if (importModal) {
            importModal.show();
        }

    });
}


/*
|--------------------------------------------------------------------------
| Reset modal whenever it opens
|--------------------------------------------------------------------------
*/

if (importQuestionsModalElement) {

    importQuestionsModalElement.addEventListener(
        'show.bs.modal',
        function () {

            importedQuestions = [];

            if (questionImportFile) {
                questionImportFile.value = '';
            }

            if (importLoading) {
                importLoading.style.display = 'none';
            }

            if (importError) {
                importError.style.display = 'none';
                importError.textContent = '';
            }

            if (importPreview) {
                importPreview.style.display = 'none';
            }

            if (importPreviewBody) {
                importPreviewBody.innerHTML = '';
            }

            if (importQuestionCount) {
                importQuestionCount.textContent = '0';
            }

            if (confirmImportBtn) {
                confirmImportBtn.disabled = true;

                confirmImportBtn.innerHTML =
                    'Import Questions';
            }

        }
    );
}


/*
|--------------------------------------------------------------------------
| Excel file selected
|--------------------------------------------------------------------------
*/

if (questionImportFile) {

    questionImportFile.addEventListener(
        'change',
        async function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            await previewExcelQuestions(file);

        }
    );
}


/*
|--------------------------------------------------------------------------
| Preview Excel
|--------------------------------------------------------------------------
*/

async function previewExcelQuestions(file)
{
    importedQuestions = [];

    if (importPreview) {
        importPreview.style.display = 'none';
    }

    if (importError) {
        importError.style.display = 'none';
        importError.textContent = '';
    }

    if (confirmImportBtn) {
        confirmImportBtn.disabled = true;
    }

    if (importLoading) {
        importLoading.style.display = 'block';
    }


    const formData = new FormData();

    formData.append(
        'question_file',
        file
    );

    formData.append(
        '_token',
        '{{ csrf_token() }}'
    );


    try {

        const response = await fetch(
            "{{ route('admin.lessons.questions.preview-import', $lesson->id) }}",
            {
                method: 'POST',

                body: formData,

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',

                    'Accept': 'application/json'
                }
            }
        );


        const data = await response.json();


        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Unable to read the Excel file.'
            );
        }


        importedQuestions =
            Array.isArray(data.questions)
                ? data.questions
                : [];


        renderImportPreview(
            importedQuestions
        );


        if (importLoading) {
            importLoading.style.display = 'none';
        }

        if (importPreview) {
            importPreview.style.display = 'block';
        }

        if (importQuestionCount) {
            importQuestionCount.textContent =
                importedQuestions.length;
        }


        if (confirmImportBtn) {
            confirmImportBtn.disabled =
                importedQuestions.length === 0;
        }


    } catch (error) {

        if (importLoading) {
            importLoading.style.display = 'none';
        }

        if (importError) {
            importError.textContent =
                error.message;

            importError.style.display = 'block';
        }

    }
}


/*
|--------------------------------------------------------------------------
| Render Preview
|--------------------------------------------------------------------------
*/

function renderImportPreview(questions)
{
    if (!importPreviewBody) {
        return;
    }


    importPreviewBody.innerHTML = '';


    questions.forEach(function (question, index) {

        const row =
            document.createElement('tr');


        const options =
            Array.isArray(question.options)
                ? question.options
                    .map(function (option) {
                        return option.text || '';
                    })
                    .filter(function (text) {
                        return text.trim() !== '';
                    })
                    .join(' | ')
                : '';


        let answer = '-';


        if (question.type === 'boolean') {

    const trueOption =
        (question.options || []).find(function (option) {
            return String(option.text).toLowerCase() === 'true';
        });

    const falseOption =
        (question.options || []).find(function (option) {
            return String(option.text).toLowerCase() === 'false';
        });

    if (editorTrueFeedback) {
        editorTrueFeedback.value =
            trueOption?.feedback || '';
    }

    if (editorFalseFeedback) {
        editorFalseFeedback.value =
            falseOption?.feedback || '';
    }

} else {

    if (editorTrueFeedback) {
        editorTrueFeedback.value = '';
    }

    if (editorFalseFeedback) {
        editorFalseFeedback.value = '';
    }
}


        row.innerHTML = `
            <td>${index + 1}</td>

            <td>
                ${escapeHtml(
                    question.text || ''
                )}
            </td>

            <td>
                ${escapeHtml(
                    question.type === 'boolean'
                        ? 'True / False'
                        : 'Multiple Choice'
                )}
            </td>

            <td>
                ${escapeHtml(options)}
            </td>

            <td class="fw-bold">
                ${escapeHtml(answer)}
            </td>
        `;


        importPreviewBody.appendChild(row);

    });
}


/*
|--------------------------------------------------------------------------
| Confirm Import
|--------------------------------------------------------------------------
*/

if (confirmImportBtn) {

    confirmImportBtn.addEventListener(
        'click',
        async function () {

            if (
                !Array.isArray(importedQuestions) ||
                importedQuestions.length === 0
            ) {
                return;
            }


            confirmImportBtn.disabled = true;

            confirmImportBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span>' +
                'Importing...';


            try {

                const response = await fetch(
                    "{{ route('admin.lessons.questions.import', $lesson->id) }}",
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}',

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        },

                        body: JSON.stringify({
                            questions:
                                importedQuestions
                        })
                    }
                );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Import failed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                if (importModal) {
                    importModal.hide();
                }


                window.location.reload();


            } catch (error) {

                if (importError) {

                    importError.textContent =
                        error.message;

                    importError.style.display =
                        'block';
                }


                confirmImportBtn.disabled =
                    false;


                confirmImportBtn.innerHTML =
                    'Import Questions';
            }

        }
    );
}

});
</script>
@endpush

@push('styles')

<style>

/* ==========================================================================
   QUIZ QUESTION MANAGER
   ========================================================================== */

#quiz-question-manager {
    display: grid !important;

    grid-template-columns: 1fr !important;
    grid-template-rows: auto auto !important;

    gap: 20px;

    width: 100%;

    min-width: 0;
}


/* ==========================================================================
   QUESTION LIST PANEL
   ========================================================================== */

#quiz-question-manager .question-list-panel {
    width: 100% !important;
    min-width: 0;

    border: 1px solid #dee2e6;
    border-radius: 10px;

    overflow: hidden;

    background: #fff;

    display: flex;
    flex-direction: column;
}


/* ==========================================================================
   QUESTION LIST TOOLBAR
   ========================================================================== */

#quiz-question-manager .question-list-toolbar {
    padding: 12px;

    background: #f8f9fa;

    border-bottom: 1px solid #dee2e6;
}


#quiz-question-manager .question-list-toolbar .form-select {
    margin-top: 8px;
}


/* ==========================================================================
   QUESTION LIST TITLE
   ========================================================================== */

#quiz-question-manager .question-list-title {
    display: flex;

    justify-content: space-between;
    align-items: center;

    padding: 12px;

    border-bottom: 1px solid #dee2e6;
}


/* ==========================================================================
   QUESTION LIST
   ========================================================================== */

#quiz-question-manager .question-list {
    width: 100%;

    max-height: 300px;

    overflow-y: auto;
    overflow-x: hidden;
}


/* ==========================================================================
   QUESTION LIST ITEM
   ========================================================================== */

#quiz-question-manager .question-list-item {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 11px 12px;

    border-bottom: 1px solid #f0f0f0;

    cursor: pointer;

    transition: background .15s ease;
}


#quiz-question-manager .question-list-item:hover {
    background: #f8f9fa;
}


#quiz-question-manager .question-list-item.active {
    background: #e9f2ff;

    border-left: 3px solid #0d6efd;
}


/* ==========================================================================
   QUESTION NUMBER
   ========================================================================== */

#quiz-question-manager .question-number {
    min-width: 28px;

    width: 28px;

    height: 28px;

    border-radius: 50%;

    background: #e9ecef;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 12px;

    font-weight: 600;

    flex-shrink: 0;
}


#quiz-question-manager .question-list-item.active .question-number {
    background: #0d6efd;

    color: #fff;
}


/* ==========================================================================
   QUESTION CONTENT
   ========================================================================== */

#quiz-question-manager .question-list-content {
    min-width: 0;

    flex: 1;
}


#quiz-question-manager .question-list-text {
    font-size: 14px;

    line-height: 1.4;

    word-break: break-word;

    overflow-wrap: anywhere;
}


#quiz-question-manager .question-list-type {
    font-size: 11px;

    color: #6c757d;

    margin-top: 3px;
}


/* ==========================================================================
   QUESTION LIST FOOTER
   ========================================================================== */

#quiz-question-manager .question-list-footer {
    padding: 10px;

    border-top: 1px solid #dee2e6;

    background: #f8f9fa;
}


/* ==========================================================================
   PAGINATION
   ========================================================================== */

#quiz-question-manager .question-pagination {
    display: flex;

    justify-content: center;

    align-items: center;

    gap: 4px;

    flex-wrap: wrap;
}


#quiz-question-manager .question-page-btn {
    border: 1px solid #dee2e6;

    background: #fff;

    border-radius: 5px;

    padding: 4px 8px;

    font-size: 12px;

    cursor: pointer;
}


#quiz-question-manager .question-page-btn:hover {
    background: #f1f3f5;
}


#quiz-question-manager .question-page-btn.active {
    background: #0d6efd;

    color: #fff;

    border-color: #0d6efd;
}


#quiz-question-manager .question-page-btn:disabled {
    opacity: .5;

    cursor: not-allowed;
}


/* ==========================================================================
   QUESTION EDITOR
   ========================================================================== */

#quiz-question-manager .question-editor-panel {
    width: 100% !important;

    min-width: 0;

    border: 1px solid #dee2e6;

    border-radius: 10px;

    background: #fff;

    overflow: hidden;
}


#quiz-question-manager .question-editor-empty {
    min-height: 350px;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

    color: #6c757d;

    padding: 30px;
}


#quiz-question-manager .question-editor-empty > i {
    font-size: 50px;

    margin-bottom: 15px;

    opacity: .35;
}


/* ==========================================================================
   EDITOR HEADER
   ========================================================================== */

#quiz-question-manager .question-editor-header {
    padding: 14px 18px;

    background: #f8f9fa;

    border-bottom: 1px solid #dee2e6;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;
}


#quiz-question-manager .question-editor-header > * {
    min-width: 0;
}


/* ==========================================================================
   EDITOR BODY
   ========================================================================== */

#quiz-question-manager .question-editor-body {
    width: 100%;

    min-width: 0;

    padding: 20px;
}


/* ==========================================================================
   OPTIONS
   ========================================================================== */

#quiz-question-manager .options-header {
    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;

    margin-bottom: 8px;
}


#quiz-question-manager .option-row {
    width: 100%;

    min-width: 0;

    border: 1px solid #dee2e6;

    border-radius: 8px;

    padding: 12px;

    margin-bottom: 10px;

    background: #fafafa;
}


#quiz-question-manager .option-main {
    width: 100%;

    min-width: 0;

    display: flex;

    align-items: flex-start;

    gap: 10px;
}


#quiz-question-manager .option-correct {
    margin-top: 10px;
}


#quiz-question-manager .option-text-wrapper {
    flex: 1;

    min-width: 0;
}


#quiz-question-manager .option-feedback {
    margin-top: 8px;
}


#quiz-question-manager .remove-option {
    border: 0;

    background: transparent;

    color: #dc3545;

    padding: 5px;

    cursor: pointer;

    flex-shrink: 0;
}


#quiz-question-manager .remove-option:hover {
    color: #a71d2a;
}


/* ==========================================================================
   BOOLEAN
   ========================================================================== */

#quiz-question-manager .boolean-answer-box {
    width: 100%;

    border: 1px solid #dee2e6;

    border-radius: 8px;

    padding: 15px;

    background: #fafafa;
}


#quiz-question-manager .boolean-choice {
    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 10px;
}


/* ==========================================================================
   EDITOR FOOTER
   ========================================================================== */

#quiz-question-manager .question-editor-footer {
    width: 100%;

    padding: 14px 18px;

    background: #f8f9fa;

    border-top: 1px solid #dee2e6;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;

    flex-wrap: wrap;
}


/* ==========================================================================
   FORM ELEMENTS
   ========================================================================== */

#quiz-question-manager input,
#quiz-question-manager textarea,
#quiz-question-manager select {
    max-width: 100%;

    min-width: 0;
}


#quiz-question-manager textarea {
    resize: vertical;
}


/* ==========================================================================
   RESPONSIVE
   ========================================================================== */

@media (max-width: 700px) {

    #quiz-question-manager .question-editor-body {
        padding: 15px;
    }


    #quiz-question-manager .question-editor-header {
        align-items: flex-start;

        flex-direction: column;
    }


    #quiz-question-manager .question-editor-footer {
        flex-direction: column;

        align-items: stretch;
    }


    #quiz-question-manager .question-editor-footer button {
        width: 100%;
    }


    #quiz-question-manager .options-header {
        align-items: flex-start;

        flex-direction: column;
    }


    #quiz-question-manager .question-list {
        max-height: 250px;
    }

}

</style>

@endpush