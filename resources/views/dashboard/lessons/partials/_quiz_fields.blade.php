<div class="mb-4 dynamic-field" id="field-quiz" style="display: none;">
    <div class="card border shadow-sm">
        <!-- Control Header & Mode Switcher -->
        <div class="card-header bg-light d-flex justify-content-between align-items-center sticky-top py-3" style="top: 0; z-index: 1020;">
            <div>
                <h5 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-list-check me-2 text-primary"></i>Quiz Questions</h5>
                <small class="text-muted">Total Questions: <span id="question-count-badge" class="badge bg-primary rounded-pill">0</span></small>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <!-- Mode Toggle Buttons -->
                <div class="btn-group btn-group-sm me-2" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="btn-mode-builder">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Interactive Builder
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btn-mode-import">
                        <i class="fa-solid fa-file-import me-1"></i> Bulk Import
                    </button>
                </div>

                <!-- Interactive Builder Actions -->
                <div id="builder-actions" class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="collapse-all-btn">
                        <i class="fa-solid fa-compress me-1"></i> Collapse All
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" id="add-question-btn">
                        <i class="fa-solid fa-plus me-1"></i> Add Question
                    </button>
                </div>
            </div>
        </div>
        
        <!-- MODE 1: Interactive Accordion Builder -->
        <div id="quiz-builder-panel" class="card-body p-3 bg-light">
            <div class="accordion" id="questions-accordion">
                <!-- Dynamic accordion items render here -->
            </div>
        </div>

        <!-- MODE 2: Bulk Import (CSV / Raw Text) -->
        <div id="quiz-import-panel" class="card-body p-4 bg-white" style="display: none;">
            <!-- Nav Tabs for Import Type -->
            <ul class="nav nav-tabs mb-3" id="importTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="file-tab" data-bs-toggle="tab" data-bs-target="#tab-file" type="button" role="tab">
                        <i class="fa-solid fa-file-csv me-1 text-success"></i> Upload CSV / Excel
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="text-tab" data-bs-toggle="tab" data-bs-target="#tab-text" type="button" role="tab">
                        <i class="fa-solid fa-paste me-1 text-info"></i> Fast Text Syntax
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="importTabContent">
                <!-- Tab 1: CSV File Import -->
                <div class="tab-pane fade show active" id="tab-file" role="tabpanel">
                    <div class="border rounded p-3 bg-light mb-3">
                        <label for="quiz_file_import" class="form-label fw-semibold text-dark">Select CSV File</label>
                        <input type="file" class="form-control" id="quiz_file_import" accept=".csv">
                        <div class="form-text mt-2">
                            Download structure template: 
                            <a href="#" id="download-template-btn" class="fw-semibold text-decoration-none"><i class="fa-solid fa-download me-1"></i>quiz_template.csv</a>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Raw Text Parsing -->
                <div class="tab-pane fade" id="tab-text" role="tabpanel">
                    <div class="mb-3">
                        <label for="raw_quiz_text" class="form-label fw-semibold text-dark">Paste Questions (Custom Format)</label>
                        <textarea id="raw_quiz_text" class="form-control font-monospace fs-7" rows="8" placeholder="Q: What is the capital of France?
* Paris
- London
- Berlin
- Madrid

Q: Is Laravel a PHP framework?
* True
- False"></textarea>
                        <small class="text-muted d-block mt-1">
                            Prefix questions with <code>Q:</code>, incorrect options with <code>-</code>, and the correct option with <code>*</code>.
                        </small>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="parse-text-btn">
                        <i class="fa-solid fa-gear me-1"></i> Process Raw Text
                    </button>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Importing will append questions to your builder list.</span>
                <button type="button" class="btn btn-success px-4" id="process-import-btn">
                    <i class="fa-solid fa-file-import me-1"></i> Append Questions to Quiz
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Accordion Question Card Template -->
<template id="question-card-template">
    <div class="accordion-item question-card border mb-3 shadow-sm rounded overflow-hidden">
        <h2 class="accordion-header">
            <button class="accordion-button py-2 fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse">
                <span class="q-number-label me-2">Q1.</span>
                <span class="badge bg-secondary ms-2 opacity-75 q-type-badge">Multiple Choice</span>
                <span class="text-truncate ms-3 fw-normal text-muted small q-title-preview">New Question...</span>
            </button>
        </h2>
        <div class="accordion-collapse collapse show">
            <div class="accordion-body bg-white border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-light text-dark border">Settings</span>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn border-0">
                        <i class="fa-solid fa-trash me-1"></i> Delete
                    </button>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold small text-muted">Question Prompt <span class="text-danger">*</span></label>
                        <input type="text" class="form-control q-text-input" placeholder="Enter question..." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Question Type</label>
                        <select class="form-select q-type-select">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="boolean">True / False</option>
                        </select>
                    </div>
                </div>

                <!-- NEW: Hint and Explanation Fields -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Hint <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea class="form-control q-hint-input" rows="2" placeholder="e.g. Think about prime numbers..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Explanation <span class="text-muted fw-normal">(Shown after answer)</span></label>
                        <textarea class="form-control q-explanation-input" rows="2" placeholder="e.g. This is correct because..."></textarea>
                    </div>
                </div>

                <div class="options-wrapper pt-2 border-top"></div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const questionsContainer = document.getElementById('questions-accordion');
    const addQuestionBtn = document.getElementById('add-question-btn');
    const collapseAllBtn = document.getElementById('collapse-all-btn');
    const questionCountBadge = document.getElementById('question-count-badge');
    const template = document.getElementById('question-card-template');
    
    // Panel Elements
    const btnBuilder = document.getElementById('btn-mode-builder');
    const btnImport = document.getElementById('btn-mode-import');
    const panelBuilder = document.getElementById('quiz-builder-panel');
    const panelImport = document.getElementById('quiz-import-panel');
    const builderActions = document.getElementById('builder-actions');

    // Import Elements
    const csvFileInput = document.getElementById('quiz_file_import');
    const rawTextArea = document.getElementById('raw_quiz_text');
    const processImportBtn = document.getElementById('process-import-btn');
    const downloadTemplateBtn = document.getElementById('download-template-btn');

    let questionIndex = 0;
    if (!questionsContainer || !template) return;

    // Switch Modes
    btnBuilder.addEventListener('click', function () {
        btnBuilder.classList.add('active');
        btnImport.classList.remove('active');
        panelBuilder.style.display = 'block';
        panelImport.style.display = 'none';
        builderActions.style.display = 'flex';
    });

    btnImport.addEventListener('click', function () {
        btnImport.classList.add('active');
        btnBuilder.classList.remove('active');
        panelImport.style.display = 'block';
        panelBuilder.style.display = 'none';
        builderActions.style.display = 'none';
    });

    function updateQuestionNumbers() {
        const cards = questionsContainer.children;
        questionCountBadge.textContent = cards.length;
        Array.from(cards).forEach((card, idx) => {
            card.querySelector('.q-number-label').textContent = `Q${idx + 1}.`;
        });
    }

    // UPDATED: Added feedback input field to option row
    function createOptionRow(qIdx, optIdx, text = '', isCorrect = false, feedback = '', isReadonly = false) {
        const div = document.createElement('div');
        div.className = 'border rounded p-2 mb-2 bg-light option-row';
        div.innerHTML = `
            <div class="input-group mb-1">
                <div class="input-group-text bg-white">
                    <input class="form-check-input mt-0" type="radio" name="questions[${qIdx}][correct_option]" value="${optIdx}" ${isCorrect ? 'checked' : ''} required title="Mark as correct answer">
                </div>
                <input type="text" name="questions[${qIdx}][options][${optIdx}][text]" class="form-control" value="${text}" placeholder="Option ${optIdx + 1}" ${isReadonly ? 'readonly' : ''} required>
                ${!isReadonly ? '<button type="button" class="btn btn-outline-danger remove-option-btn"><i class="fa-solid fa-times"></i></button>' : ''}
            </div>
            <div class="ms-4">
                <input type="text" name="questions[${qIdx}][options][${optIdx}][feedback]" class="form-control form-control-sm text-muted" value="${feedback}" placeholder="Option Feedback (optional)">
            </div>
        `;
        return div;
    }

    function renderOptionsBody(qCard, qIdx, qType, optionsData = null) {
        const wrapper = qCard.querySelector('.options-wrapper');
        wrapper.innerHTML = '';

        const label = document.createElement('label');
        label.className = 'form-label fw-semibold text-muted small mb-2';
        const container = document.createElement('div');
        container.className = 'options-container';

        if (qType === 'boolean') {
            label.textContent = 'Correct Answer';
            const correctOpt = optionsData ? optionsData.findIndex(o => o.isCorrect) : 0;
            const trueFb = optionsData && optionsData[0] ? (optionsData[0].feedback || '') : '';
            const falseFb = optionsData && optionsData[1] ? (optionsData[1].feedback || '') : '';

            container.appendChild(createOptionRow(qIdx, 0, 'True', correctOpt === 0, trueFb, true));
            container.appendChild(createOptionRow(qIdx, 1, 'False', correctOpt === 1, falseFb, true));
            wrapper.appendChild(label);
            wrapper.appendChild(container);
        } else {
            label.textContent = 'Answer Options (Select correct answer radio button)';
            if (optionsData && optionsData.length > 0) {
                optionsData.forEach((opt, oIdx) => {
                    container.appendChild(createOptionRow(qIdx, oIdx, opt.text, opt.isCorrect, opt.feedback || ''));
                });
            } else {
                container.appendChild(createOptionRow(qIdx, 0, '', true, ''));
                container.appendChild(createOptionRow(qIdx, 1, '', false, ''));
            }

            const addOptBtn = document.createElement('button');
            addOptBtn.type = 'button';
            addOptBtn.className = 'btn btn-sm btn-outline-secondary add-option-btn mt-1';
            addOptBtn.innerHTML = '<i class="fa-solid fa-plus me-1"></i> Add Option';

            wrapper.appendChild(label);
            wrapper.appendChild(container);
            wrapper.appendChild(addOptBtn);
        }
    }

    // UPDATED: Added hint and explanation bindings
    function addQuestion(title = '', type = 'multiple_choice', hint = '', explanation = '', options = null) {
        const currentQIndex = questionIndex++;
        const clone = template.content.cloneNode(true);
        const qCard = clone.querySelector('.question-card');
        const collapseId = `collapse-q-${currentQIndex}`;
        
        const collapseBtn = qCard.querySelector('.accordion-button');
        const collapseTarget = qCard.querySelector('.accordion-collapse');
        const qTextInput = qCard.querySelector('.q-text-input');
        const qHintInput = qCard.querySelector('.q-hint-input');
        const qExplanationInput = qCard.querySelector('.q-explanation-input');
        const qTypeSelect = qCard.querySelector('.q-type-select');
        const qTitlePreview = qCard.querySelector('.q-title-preview');
        const qTypeBadge = qCard.querySelector('.q-type-badge');

        collapseBtn.setAttribute('data-bs-target', `#${collapseId}`);
        collapseTarget.id = collapseId;

        qTextInput.name = `questions[${currentQIndex}][text]`;
        qHintInput.name = `questions[${currentQIndex}][hint]`;
        qExplanationInput.name = `questions[${currentQIndex}][explanation]`;
        qTypeSelect.name = `questions[${currentQIndex}][type]`;

        if (title) {
            qTextInput.value = title;
            qTitlePreview.textContent = title;
        }

        if (hint) qHintInput.value = hint;
        if (explanation) qExplanationInput.value = explanation;

        if (type) {
            qTypeSelect.value = type;
            qTypeBadge.textContent = qTypeSelect.options[qTypeSelect.selectedIndex].text;
        }

        qTextInput.addEventListener('input', (e) => {
            qTitlePreview.textContent = e.target.value.trim() || 'New Question...';
        });

        qTypeSelect.addEventListener('change', (e) => {
            qTypeBadge.textContent = e.target.options[e.target.selectedIndex].text;
            renderOptionsBody(qCard, currentQIndex, e.target.value);
        });

        questionsContainer.appendChild(qCard);
        renderOptionsBody(qCard, currentQIndex, type, options);
        updateQuestionNumbers();
    }

    // UPDATED: CSV Parser reads hint and explanation columns
    function parseCSV(text) {
        const lines = text.split('\n').filter(line => line.trim() !== '');
        if (lines.length <= 1) return [];

        const parsedQuestions = [];
        // Expected columns: Question, Type, Hint, Explanation, CorrectOptionIndex, Option1, Option2...
        for (let i = 1; i < lines.length; i++) {
            const cols = lines[i].split(',').map(c => c.trim().replace(/^"(.*)"$/, '$1'));
            if (cols.length < 6) continue;

            const questionText = cols[0];
            const qType = cols[1].toLowerCase() === 'boolean' ? 'boolean' : 'multiple_choice';
            const hint = cols[2] || '';
            const explanation = cols[3] || '';
            const correctIndex = parseInt(cols[4]) || 0;
            const options = [];

            for (let j = 5; j < cols.length; j++) {
                if (cols[j]) {
                    options.push({ text: cols[j], isCorrect: (j - 5) === correctIndex, feedback: '' });
                }
            }

            if (questionText && options.length > 0) {
                parsedQuestions.push({ 
                    title: questionText, 
                    type: qType, 
                    hint: hint, 
                    explanation: explanation, 
                    options: options 
                });
            }
        }
        return parsedQuestions;
    }

    // UPDATED: Text Syntax Parser reads H: (Hint) and E: (Explanation)
    function parseTextSyntax(text) {
        const blocks = text.split(/\n\s*\n/);
        const parsedQuestions = [];

        blocks.forEach(block => {
            const lines = block.split('\n').map(l => l.trim()).filter(l => l !== '');
            if (lines.length === 0) return;

            let title = '';
            let hint = '';
            let explanation = '';
            const options = [];

            lines.forEach(line => {
                if (line.startsWith('Q:')) {
                    title = line.replace(/^Q:\s*/, '');
                } else if (line.startsWith('H:')) {
                    hint = line.replace(/^H:\s*/, '');
                } else if (line.startsWith('E:')) {
                    explanation = line.replace(/^E:\s*/, '');
                } else if (line.startsWith('*')) {
                    options.push({ text: line.replace(/^\*\s*/, ''), isCorrect: true, feedback: '' });
                } else if (line.startsWith('-')) {
                    options.push({ text: line.replace(/^-\s*/, ''), isCorrect: false, feedback: '' });
                }
            });

            if (title && options.length > 0) {
                const isBool = options.length === 2 && 
                              options.some(o => o.text.toLowerCase() === 'true') && 
                              options.some(o => o.text.toLowerCase() === 'false');

                parsedQuestions.push({
                    title: title,
                    type: isBool ? 'boolean' : 'multiple_choice',
                    hint: hint,
                    explanation: explanation,
                    options: options
                });
            }
        });

        return parsedQuestions;
    }

    // Process Import Button Click
    processImportBtn.addEventListener('click', function () {
        const activeTab = document.querySelector('#importTab .nav-link.active').id;
        let importedList = [];

        if (activeTab === 'file-tab') {
            const file = csvFileInput.files[0];
            if (!file) {
                alert('Please select a CSV file first.');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                importedList = parseCSV(e.target.result);
                applyImportedQuestions(importedList);
            };
            reader.readAsText(file);
        } else {
            const rawText = rawTextArea.value;
            if (!rawText.trim()) {
                alert('Please paste question syntax first.');
                return;
            }
            importedList = parseTextSyntax(rawText);
            applyImportedQuestions(importedList);
        }
    });

    function applyImportedQuestions(list) {
        if (list.length === 0) {
            alert('No valid questions were parsed. Please check your format.');
            return;
        }

        list.forEach(q => addQuestion(q.title, q.type, q.hint, q.explanation, q.options));
        alert(`Successfully appended ${list.length} questions!`);
        btnBuilder.click();
    }

    // CSV Sample Download Generator with hint/explanation columns
    downloadTemplateBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const csvContent = "data:text/csv;charset=utf-8," + 
            "Question,Type,Hint,Explanation,CorrectOptionIndex,Option1,Option2,Option3,Option4\n" +
            "What is the capital of Myanmar?,multiple_choice,Located in middle country,Declared capital in 2005,0,Naypyidaw,Yangon,Mandalay,Bagan\n" +
            "Is PHP a server-side language?,boolean,,,0,True,False,,";
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "quiz_template.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Builder Accordion Events
    let allCollapsed = false;
    collapseAllBtn.addEventListener('click', function() {
        allCollapsed = !allCollapsed;
        const collapses = questionsContainer.querySelectorAll('.accordion-collapse');
        collapses.forEach(el => {
            if (allCollapsed) el.classList.remove('show');
            else el.classList.add('show');
        });
        collapseAllBtn.innerHTML = allCollapsed 
            ? '<i class="fa-solid fa-expand me-1"></i> Expand All'
            : '<i class="fa-solid fa-compress me-1"></i> Collapse All';
    });

    questionsContainer.addEventListener('click', function (e) {
        if (e.target.closest('.add-option-btn')) {
            const qCard = e.target.closest('.question-card');
            const qIdx = qCard.querySelector('.q-type-select').name.match(/\d+/)[0];
            const optionsContainer = qCard.querySelector('.options-container');
            const newOptIdx = optionsContainer.querySelectorAll('.option-row').length;
            optionsContainer.appendChild(createOptionRow(qIdx, newOptIdx));
        }

        if (e.target.closest('.remove-option-btn')) {
            const row = e.target.closest('.option-row');
            const container = row.parentElement;
            if (container.querySelectorAll('.option-row').length > 2) row.remove();
            else alert('Multiple choice questions require at least 2 options.');
        }

        if (e.target.closest('.remove-question-btn')) {
            if (questionsContainer.children.length > 1) {
                e.target.closest('.question-card').remove();
                updateQuestionNumbers();
            } else alert('Quizzes must contain at least 1 question.');
        }
    });

    window.addEventListener('quiz-type-selected', function() {
        if (questionsContainer.children.length === 0) addQuestion();
    });

    addQuestionBtn.addEventListener('click', () => addQuestion());
});
</script>