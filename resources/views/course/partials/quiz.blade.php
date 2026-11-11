@push('styles')

<style>

/* =========================================================
   QUIZ BASE
========================================================= */

.quiz-container {
    width: 100%;
}

.quiz-option-area {
    width: 100%;
}

.quiz-container-wrapper { 
    width: 100%; 
    min-height: calc(100vh - 150px); 
    box-sizing: border-box; 
    display: flex; 
    flex-direction: column; 
}

#quizStartCard { width: 100%; max-width: 650px; margin: auto !important; display: flex; flex-direction: column; justify-content: center; }
#quizStartCard .card-body { width: 100%; }

@media (max-width: 767.98px) { .quiz-container-wrapper { min-height: calc(100vh - 220px); padding: 20px 14px !important; } #quizStartCard { width: 100%; max-width: 100%; margin: auto !important; } }
/* =========================================================
   QUIZ FORM
========================================================= */

.quiz-container-wrapper #quizForm {
    width: 100%;
    max-width: 100%;

    flex: 1;
}

.quiz-step {
    width: 100%;
    max-width: 100%;

    min-height: calc(100vh - 230px);

    display: flex;
    flex-direction: column;

    box-sizing: border-box;
}


/* =========================================================
   QUESTION TEXT
========================================================= */

.quiz-question-text {
    color: #212529;

    font-weight: 700;

    font-size: 1.15rem;

    line-height: 1.55;

    overflow-wrap: break-word;
    word-break: break-word;
}


/* =========================================================
   OPTION AREA
========================================================= */

.options-container {
    width: 100%;
    max-width: 100%;
}


/* =========================================================
   OPTION LABEL
========================================================= */

.option-label {
    cursor: pointer !important;

    transition: all 0.2s ease-in-out;

    user-select: none;

    width: 100%;

    min-height: 58px;

    line-height: 1.5;

    overflow-wrap: break-word;
    word-break: break-word;

    box-sizing: border-box;
}

.option-label:hover {
    border-color: #0d6efd !important;

    background-color: #f8f9fa !important;
}

.option-label.active-option {
    border-color: #0d6efd !important;

    background-color: #e7f1ff !important;
}

.option-label.correct-option {
    border-color: #198754 !important;

    background-color: #d1e7dd !important;

    color: #0f5132;
}

.option-label.incorrect-option {
    border-color: #dc3545 !important;

    background-color: #f8d7da !important;

    color: #842029;
}


/* =========================================================
   RADIO
========================================================= */

.quiz-radio {
    width: 1.25rem !important;
    height: 1.25rem !important;

    min-width: 1.25rem !important;
    min-height: 1.25rem !important;

    max-width: 1.25rem !important;
    max-height: 1.25rem !important;

    border-radius: 50% !important;

    flex-shrink: 0 !important;

    aspect-ratio: 1 / 1 !important;

    margin-top: 0 !important;
}


/* =========================================================
   OPTION TEXT
========================================================= */

.option-label > span {
    flex: 1;

    min-width: 0;

    overflow-wrap: break-word;
    word-break: break-word;
}


/* =========================================================
   HINT
========================================================= */

.hint-box {
    line-height: 1.6;

    overflow-wrap: break-word;
    word-break: break-word;
}


/* =========================================================
   FEEDBACK
========================================================= */

.feedback-container {
    width: 100%;

    overflow-wrap: break-word;
    word-break: break-word;
}

.feedback-container .alert {
    line-height: 1.6;

    overflow-wrap: break-word;
    word-break: break-word;
}


/* =========================================================
   ACTION AREA
========================================================= */

.quiz-action-area {
    width: 100%;

    /*
     * Push action button toward
     * the bottom of the quiz.
     */
    margin-top: auto !important;
}

.quiz-action-area .action-btn {
    padding-left: 20px !important;
    padding-right: 20px !important;
}

button.btn {
    padding-left: 20px !important;
    padding-right: 20px !important;
}


/* =========================================================
   START QUIZ
========================================================= */

#quizStartCard {
    width: 100%;
}

#quizStartCard h3 {
    line-height: 1.4;
}


/* =========================================================
   COMPLETION
========================================================= */

#quizCompletedCard {
    width: 100%;
}

#quizCompletedActions {
    width: 100%;
}


/* =========================================================
   DESKTOP
========================================================= */

@media (min-width: 768px) {

    .quiz-container-wrapper {
        min-height: calc(100vh - 150px);
    }

    .quiz-container-wrapper .quiz-step {
        min-height: calc(100vh - 230px);
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {

    .quiz-container-wrapper {
        min-height: calc(100vh - 220px);

        padding: 20px 14px !important;
    }

    .quiz-container-wrapper .quiz-step {
        min-height: calc(100vh - 280px);
    }


    /* =====================================================
       START SCREEN
    ===================================================== */

    #quizStartCard {
        padding-top: 25px !important;

        padding-bottom: 25px !important;
    }

    #quizStartCard .fa-4x {
        font-size: 3rem;
    }

    #quizStartCard h3 {
        font-size: 1.25rem;

        line-height: 1.4;

        margin-top: 15px !important;
    }

    #quizStartCard p {
        font-size: 0.9rem !important;

        line-height: 1.6;
    }

    #startQuizBtn {
        width: 100%;

        min-height: 50px;

        padding-left: 20px !important;
        padding-right: 20px !important;
    }


    /* =====================================================
       QUESTION HEADER
    ===================================================== */

    .quiz-step > .d-flex {
        align-items: flex-start !important;

        gap: 10px;

        flex-wrap: wrap;
    }

    .quiz-step > .d-flex .badge {
        font-size: 0.75rem;

        padding: 7px 10px !important;

        white-space: nowrap;
    }

    .toggle-hint-btn {
        margin-left: auto;

        white-space: nowrap;

        font-size: 0.8rem !important;
    }


    /* =====================================================
       QUESTION TEXT
    ===================================================== */

    .quiz-question-text {
        font-size: 1.05rem !important;

        line-height: 1.55;

        margin-bottom: 20px !important;
    }


    /* =====================================================
       OPTIONS
    ===================================================== */

    .options-container {
        gap: 10px !important;

        margin-bottom: 20px !important;
    }

    .option-label {
        min-height: 56px;

        padding: 13px 12px !important;

        gap: 12px !important;

        border-radius: 10px !important;

        font-size: 0.95rem;

        align-items: flex-start !important;
    }

    .option-label .quiz-radio {
        margin-top: 2px !important;
    }


    /* =====================================================
       HINT
    ===================================================== */

    .hint-box {
        font-size: 0.85rem !important;

        padding: 10px 12px !important;

        margin-bottom: 18px !important;
    }


    /* =====================================================
       FEEDBACK
    ===================================================== */

    .feedback-container {
        margin-bottom: 20px !important;
    }

    .feedback-container .alert {
        padding: 12px !important;

        font-size: 0.9rem;

        border-radius: 10px;
    }

    .feedback-container .alert .small {
        font-size: 0.82rem !important;

        line-height: 1.6;
    }


    /* =====================================================
       ACTION AREA
    ===================================================== */

    .quiz-action-area {
        margin-top: auto !important;

        padding-top: 15px !important;
    }

    .quiz-action-area .action-btn {
        width: 100%;

        min-height: 48px;

        font-size: 0.9rem;
    }


    /* =====================================================
       COMPLETION
    ===================================================== */

    #quizCompletedCard {
        padding-top: 25px !important;

        padding-bottom: 25px !important;
    }

    #quizCompletedCard .fa-4x {
        font-size: 3rem;
    }

    #resultTitle {
        font-size: 1.25rem;

        line-height: 1.4;
    }

    #resultScore {
        font-size: 0.95rem !important;

        line-height: 1.6;
    }

    #quizCompletedActions > div {
        flex-direction: column;

        width: 100%;

        gap: 10px !important;
    }

    #quizCompletedActions .btn {
        width: 100%;

        min-height: 48px;
    }

}


/* =========================================================
   VERY SMALL PHONES
========================================================= */

@media (max-width: 375px) {

    .quiz-container-wrapper {
        min-height: calc(100vh - 220px);

        padding: 16px 11px !important;
    }

    .quiz-container-wrapper .quiz-step {
        min-height: calc(100vh - 280px);
    }

    .quiz-question-text {
        font-size: 1rem !important;
    }

    .option-label {
        padding: 12px 10px !important;

        font-size: 0.9rem;

        gap: 10px !important;
    }

    .quiz-radio {
        width: 1.15rem !important;

        height: 1.15rem !important;

        min-width: 1.15rem !important;

        min-height: 1.15rem !important;
    }

    .toggle-hint-btn {
        font-size: 0.75rem !important;
    }

}


/* =========================================================
   ACCESSIBILITY
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .option-label {
        transition: none !important;
    }

}

</style>

@endpush


<div class="quiz-container-wrapper p-4 p-md-5">

    @php

        $quizQuestions = (isset($questions) && $questions->isNotEmpty())
            ? $questions
            : $lesson->questions;

        $totalQuestions = $quizQuestions->count();

    @endphp


    <!-- =========================================================
         START QUIZ
    ========================================================== -->

    <div
        id="quizStartCard"
        class="text-center py-4"
    >

        <div class="mb-3 text-primary">

            <i class="fas fa-file-signature fa-4x"></i>

        </div>

        <h3 class="fw-bold mt-3">

            Ready to test your knowledge?

        </h3>

        <p class="fs-6 text-muted mb-4">

            This quiz contains

            <strong>
                {{ $totalQuestions }}
            </strong>

            {{ Str::plural('question', $totalQuestions) }}.

        </p>

        <button
            type="button"
            id="startQuizBtn"
            class="btn btn-primary btn-lg rounded-3 px-5 py-2"
        >

            <i class="fas fa-play me-2"></i>

            Start Quiz

        </button>

    </div>


    <!-- =========================================================
         COMPLETED QUIZ
    ========================================================== -->

    <div
        id="quizCompletedCard"
        class="text-center py-4"
        style="display: none;"
    >

        <div
            class="mb-3"
            id="resultIcon"
        ></div>

        <h3
            class="fw-bold mt-3"
            id="resultTitle"
        ></h3>

        <p
            class="fs-5 text-muted mb-4"
            id="resultScore"
        ></p>

        <div id="quizCompletedActions">

            <button
                onclick="window.location.reload()"
                class="btn btn-outline-primary rounded-3 px-4"
            >

                <i class="fas fa-redo me-1"></i>

                Retake Quiz

            </button>

        </div>

    </div>


    <!-- =========================================================
         QUIZ FORM
    ========================================================== -->

    <form
        id="quizForm"
        action="{{ route('courses.lessons.submit', [$course->id, $lesson->id]) }}"
        method="POST"
        style="display: none;"
    >

        @csrf


        @forelse($quizQuestions as $qIndex => $question)

            <!-- =================================================
                 QUESTION
            ================================================== -->

            <div
                class="quiz-step"
                data-step="{{ $qIndex }}"
                data-question-id="{{ $question->id }}"
                style="display: none;"
            >


                <!-- QUESTION HEADER -->

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-center
                        mb-3
                    "
                >

                    <span
                        class="
                            badge
                            bg-primary-subtle
                            text-primary
                            px-3
                            py-2
                            rounded-pill
                        "
                    >

                        Question
                        {{ $qIndex + 1 }}
                        of
                        {{ $totalQuestions }}

                    </span>


                    @if(!empty($question->hint))

                        <button
                            type="button"
                            class="
                                btn
                                btn-link
                                btn-sm
                                text-decoration-none
                                toggle-hint-btn
                                p-0
                                text-warning
                                fw-semibold
                            "
                        >

                            <i class="far fa-lightbulb me-1"></i>

                            Need a Hint?

                        </button>

                    @endif

                </div>


                <!-- HINT -->

                @if(!empty($question->hint))

                    <div
                        class="
                            alert
                            alert-warning
                            alert-dismissible
                            fade
                            show
                            hint-box
                            mb-3
                            py-2
                            px-3
                            small
                        "
                        style="display: none;"
                    >

                        <i class="fas fa-info-circle me-1"></i>

                        <strong>Hint:</strong>

                        {{ $question->hint }}

                    </div>

                @endif


                <!-- QUESTION TEXT -->

                <h6 class="quiz-question-text mb-4">

                    {{ $question->question_text }}

                </h6>


                <!-- OPTIONS -->

                @if($question->type === 'multiple_choice')

                    <div
                        class="
                            d-flex
                            flex-column
                            gap-2
                            mb-4
                            options-container
                        "
                    >

                        @foreach($question->options as $option)

                            <label
                                class="
                                    border
                                    rounded-3
                                    p-3
                                    d-flex
                                    align-items-center
                                    gap-3
                                    bg-light
                                    option-label
                                "
                            >

                                <input
                                    type="radio"
                                    name="answer_{{ $question->id }}"
                                    value="{{ $option->id }}"
                                    class="
                                        form-check-input
                                        mt-0
                                        quiz-radio
                                    "
                                >

                                <span>

                                    {{ $option->option_text }}

                                </span>

                            </label>

                        @endforeach

                    </div>


                @elseif($question->type === 'boolean')

                    <div
                        class="
                            d-flex
                            flex-column
                            gap-2
                            mb-4
                            options-container
                        "
                    >

                        <label
                            class="
                                border
                                rounded-3
                                p-3
                                d-flex
                                align-items-center
                                gap-3
                                bg-light
                                option-label
                            "
                        >

                            <input
                                type="radio"
                                name="answer_{{ $question->id }}"
                                value="1"
                                class="
                                    form-check-input
                                    mt-0
                                    quiz-radio
                                "
                            >

                            <span>True</span>

                        </label>


                        <label
                            class="
                                border
                                rounded-3
                                p-3
                                d-flex
                                align-items-center
                                gap-3
                                bg-light
                                option-label
                            "
                        >

                            <input
                                type="radio"
                                name="answer_{{ $question->id }}"
                                value="0"
                                class="
                                    form-check-input
                                    mt-0
                                    quiz-radio
                                "
                            >

                            <span>False</span>

                        </label>

                    </div>

                @endif


                <!-- FEEDBACK -->

                <div
                    class="feedback-container mb-4"
                    style="display: none;"
                ></div>


                <!-- ACTION BUTTON -->

                <div
                    class="
                        quiz-action-area
                        d-flex
                        justify-content-end
                        mt-4
                        pt-3
                        border-top
                    "
                >

                    <button
                        type="button"
                        class="
                            btn
                            btn-secondary
                            px-4
                            rounded-3
                            action-btn
                        "
                        data-state="submit"
                        disabled
                    >

                        Submit Answer

                    </button>

                </div>

            </div>

        @empty

            <div class="text-center text-muted py-4">

                No questions added yet.

            </div>

        @endforelse

    </form>

</div>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const quizStartCard =
        document.getElementById('quizStartCard');

    const startQuizBtn =
        document.getElementById('startQuizBtn');

    const quizForm =
        document.getElementById('quizForm');

    const nextLessonBar =
        document.getElementById('nextLessonBar');


    if (!quizForm) {
        return;
    }


    /* =========================================================
       START QUIZ
    ========================================================== */

    if (startQuizBtn) {

        startQuizBtn.addEventListener(
            'click',
            function () {

                quizStartCard.style.display = 'none';

                quizForm.style.display = 'block';

                if (nextLessonBar) {
                    nextLessonBar.style.display = 'none';
                }

                const firstStep =
                    quizForm.querySelector(
                        '.quiz-step[data-step="0"]'
                    );


                if (firstStep) {

                    firstStep.style.display = 'flex';

                }

            }
        );

    }


    /* =========================================================
       HINT
    ========================================================== */

    quizForm.addEventListener(
        'click',
        function (e) {

            const hintBtn =
                e.target.closest('.toggle-hint-btn');


            if (!hintBtn) {
                return;
            }


            const step =
                hintBtn.closest('.quiz-step');


            const hintBox =
                step.querySelector('.hint-box');


            if (hintBox) {

                hintBox.style.display =
                    hintBox.style.display === 'none'
                        ? 'block'
                        : 'none';

            }

        }
    );


    /* =========================================================
       RADIO SELECTION
    ========================================================== */

    quizForm.addEventListener(
        'change',
        function (e) {

            const radio =
                e.target.closest('.quiz-radio');


            if (!radio) {
                return;
            }


            const step =
                radio.closest('.quiz-step');


            const container =
                radio.closest('.options-container');


            if (container) {

                container
                    .querySelectorAll('.option-label')
                    .forEach(function (label) {

                        label.classList.remove(
                            'active-option'
                        );

                    });


                const label =
                    radio.closest('.option-label');


                if (label) {

                    label.classList.add(
                        'active-option'
                    );

                }

            }


            const actionBtn =
                step.querySelector('.action-btn');


            if (
                actionBtn &&
                actionBtn.getAttribute('data-state') === 'submit'
            ) {

                actionBtn.classList.remove(
                    'btn-secondary'
                );

                actionBtn.classList.add(
                    'btn-primary'
                );

                actionBtn.removeAttribute(
                    'disabled'
                );

            }

        }
    );


    /* =========================================================
       ACTION BUTTON
       SUBMIT -> NEXT -> FINISH
    ========================================================== */

    quizForm.addEventListener(
        'click',
        async function (e) {

            const actionBtn =
                e.target.closest('.action-btn');


            if (!actionBtn) {
                return;
            }


            const currentStep =
                actionBtn.closest('.quiz-step');


            const currentState =
                actionBtn.getAttribute(
                    'data-state'
                );


            const stepIndex =
                parseInt(
                    currentStep.getAttribute(
                        'data-step'
                    ),
                    10
                );


            const questionId =
                currentStep.getAttribute(
                    'data-question-id'
                );


            /* =================================================
               STATE A: SUBMIT
            ================================================= */

            if (currentState === 'submit') {

                const selectedRadio =
                    currentStep.querySelector(
                        '.quiz-radio:checked'
                    );


                if (!selectedRadio) {
                    return;
                }


                actionBtn.setAttribute(
                    'disabled',
                    'true'
                );


                actionBtn.innerText =
                    'Checking...';


                const formData =
                    new FormData();


                formData.append(
                    '_token',
                    '{{ csrf_token() }}'
                );


                formData.append(
                    'question_id',
                    questionId
                );


                formData.append(
                    'step_index',
                    stepIndex
                );


                formData.append(
                    'answer',
                    selectedRadio.value
                );


                try {

                    const response =
                        await fetch(
                            quizForm.action,
                            {
                                method: 'POST',

                                headers: {
                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'Accept':
                                        'application/json'
                                },

                                body: formData
                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP error! status: ${response.status}`
                        );

                    }


                    const data =
                        await response.json();


                    // /* Lock inputs */

                    // currentStep
                    //     .querySelectorAll(
                    //         '.quiz-radio'
                    //     )
                    //     .forEach(function (radio) {

                    //         radio.disabled = true;

                    //     });


                    // /* Feedback */

                    // const feedbackBox =
                    //     currentStep.querySelector(
                    //         '.feedback-container'
                    //     );


                    // const selectedLabel =
                    //     selectedRadio.closest(
                    //         '.option-label'
                    //     );


                    // selectedLabel.classList.remove(
                    //     'active-option'
                    // );


                    // let messageBody = '';


                    // if (data.feedback) {

                    //     messageBody +=
                    //         `<div class="mt-1 small">${data.feedback}</div>`;

                    // }


                    // if (data.explanation) {

                    //     messageBody +=
                    //         `<div class="mt-2 small text-secondary border-top pt-2">
                    //             <strong>Explanation:</strong>
                    //             ${data.explanation}
                    //         </div>`;

                    // }


                    // if (data.is_correct) {

                    //     selectedLabel.classList.add(
                    //         'correct-option'
                    //     );


                    //     feedbackBox.innerHTML = `
                    //         <div class="alert alert-success mb-0 py-3">

                    //             <div class="d-flex align-items-center gap-2 fw-semibold">

                    //                 <i class="fas fa-check-circle fs-5"></i>

                    //                 Correct!

                    //             </div>

                    //             ${messageBody}

                    //         </div>
                    //     `;

                    // } else {

                    //     selectedLabel.classList.add(
                    //         'incorrect-option'
                    //     );


                    //     feedbackBox.innerHTML = `
                    //         <div class="alert alert-danger mb-0 py-3">

                    //             <div class="d-flex align-items-center gap-2 fw-semibold">

                    //                 <i class="fas fa-times-circle fs-5"></i>

                    //                 Incorrect.

                    //             </div>

                    //             ${messageBody}

                    //         </div>
                    //     `;

                    // }


                    // feedbackBox.style.display =
                    //     'block';

                    /* Lock inputs */
                    currentStep
                        .querySelectorAll('.quiz-radio')
                        .forEach(function (radio) {
                            radio.disabled = true;
                        });


                    /* No correct/incorrect feedback shown */


                    actionBtn.removeAttribute('disabled');


                    if (data.is_completed) {

                        actionBtn.setAttribute(
                            'data-state',
                            'finish'
                        );

                        actionBtn.className =
                            'btn btn-success px-4 rounded-3 action-btn';

                        actionBtn.innerHTML =
                            'Finish & See Results';

                        actionBtn.dataset.summary =
                            JSON.stringify(
                                data.summary || {}
                            );

                    } else {

                        actionBtn.setAttribute(
                            'data-state',
                            'next'
                        );

                        actionBtn.className =
                            'btn btn-primary px-4 rounded-3 action-btn';

                        actionBtn.innerHTML =
                            'Next Question <i class="fas fa-arrow-right ms-1"></i>';

                    }


                    /* =================================================
                       NEXT / FINISH
                    ================================================= */

                    actionBtn.removeAttribute(
                        'disabled'
                    );


                    if (data.is_completed) {

                        actionBtn.setAttribute(
                            'data-state',
                            'finish'
                        );


                        actionBtn.className =
                            'btn btn-success px-4 rounded-3 action-btn';


                        actionBtn.innerHTML =
                            'Finish & See Results';


                        actionBtn.dataset.summary =
                            JSON.stringify(
                                data.summary || {}
                            );

                    } else {

                        actionBtn.setAttribute(
                            'data-state',
                            'next'
                        );


                        actionBtn.className =
                            'btn btn-primary px-4 rounded-3 action-btn';


                        actionBtn.innerHTML =
                            'Next Question <i class="fas fa-arrow-right ms-1"></i>';

                    }

                } catch (err) {

                    console.error(
                        'Quiz submission error:',
                        err
                    );


                    actionBtn.innerText =
                        'Submit Answer';


                    actionBtn.removeAttribute(
                        'disabled'
                    );

                }

            }


            /* =================================================
               STATE B: NEXT
            ================================================= */

            else if (currentState === 'next') {

                const nextStep =
                    quizForm.querySelector(
                        `.quiz-step[data-step="${stepIndex + 1}"]`
                    );


                if (nextStep) {

                    currentStep.style.display =
                        'none';


                    nextStep.style.display =
                        'flex';


                    if (
                        window.innerWidth <= 767
                    ) {

                        const rect =
                            nextStep.getBoundingClientRect();


                        const offset =
                            window.scrollY +
                            rect.top -
                            80;


                        window.scrollTo({

                            top: Math.max(
                                0,
                                offset
                            ),

                            behavior: 'smooth'

                        });

                    }

                }

            }


            /* =================================================
               STATE C: FINISH
            ================================================= */

            else if (currentState === 'finish') {

                let summary = {};


                try {

                    summary =
                        JSON.parse(
                            actionBtn.dataset.summary ||
                            '{}'
                        );

                } catch (err) {

                    console.error(
                        'Error parsing quiz summary:',
                        err
                    );

                }


                quizForm.style.display =
                    'none';

                if (nextLessonBar) {
                    nextLessonBar.style.display = '';
                }


                const card =
                    document.getElementById(
                        'quizCompletedCard'
                    );


                const icon =
                    document.getElementById(
                        'resultIcon'
                    );


                const title =
                    document.getElementById(
                        'resultTitle'
                    );


                const score =
                    document.getElementById(
                        'resultScore'
                    );


                const actionsArea =
                    document.getElementById(
                        'quizCompletedActions'
                    );


                /* =================================================
                   PASSED
                ================================================= */

                if (summary.passed) {

                    icon.innerHTML =
                        '<i class="fas fa-check-circle text-success fa-4x"></i>';


                    title.className =
                        'fw-bold mt-3 text-success';


                    title.innerText =
                        'Congratulations! You Passed';


                    score.innerHTML =
                        `You scored <strong>${summary.score_percentage}%</strong>
                        (${summary.correct_count} out of
                        ${summary.total_questions} correct)`;


                    actionsArea.innerHTML = `
                        <div class="d-flex justify-content-center gap-2">

                            <button
                                onclick="window.location.reload()"
                                class="btn btn-success rounded-3 px-4"
                            >

                                <i class="fas fa-arrow-right me-1"></i>

                                Continue Course

                            </button>


                            <button
                                onclick="window.location.reload()"
                                class="btn btn-outline-secondary rounded-3 px-3"
                            >

                                <i class="fas fa-redo me-1"></i>

                                Retake

                            </button>

                        </div>
                    `;

                }


                /* =================================================
                   FAILED
                ================================================= */

                else {

                    icon.innerHTML =
                        '<i class="fas fa-times-circle text-danger fa-4x"></i>';


                    title.className =
                        'fw-bold mt-3 text-danger';


                    title.innerText =
                        'Quiz Not Passed';


                    score.innerHTML =
                        `You scored <strong>${summary.score_percentage}%</strong>
                        (${summary.correct_count} out of
                        ${summary.total_questions} correct).
                        You need 70% to pass.`;


                    actionsArea.innerHTML = `
                        <button
                            onclick="window.location.reload()"
                            class="btn btn-outline-primary rounded-3 px-4"
                        >

                            <i class="fas fa-redo me-1"></i>

                            Retake Quiz

                        </button>
                    `;

                }


                card.style.display =
                    'block';


                /* Scroll to result */

                if (window.innerWidth <= 767) {

                    window.scrollTo({

                        top: 0,

                        behavior: 'smooth'

                    });

                }

            }

        }
    );

});

</script>

@endpush