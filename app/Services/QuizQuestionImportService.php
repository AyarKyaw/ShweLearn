<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;

class QuizQuestionImportService
{
    public function preview(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(
            null,
            true,
            true,
            true
        );

        if (count($rows) < 2) {
            throw new RuntimeException(
                'The Excel file does not contain any question rows.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read headers
        |--------------------------------------------------------------------------
        */

        $headers = [];

        foreach ($rows[1] as $column => $value) {
            $headers[$column] = strtolower(
                trim((string) $value)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Required Excel columns
        |--------------------------------------------------------------------------
        */

        $requiredHeaders = [
            'question',
            'type',

            'option_a',
            'option_a_feedback',

            'option_b',
            'option_b_feedback',

            'option_c',
            'option_c_feedback',

            'option_d',
            'option_d_feedback',

            'correct_option',

            'hint',
        ];


        $missingHeaders = [];

        foreach ($requiredHeaders as $required) {

            if (!in_array($required, $headers, true)) {

                $missingHeaders[] = $required;
            }
        }


        if (!empty($missingHeaders)) {

            throw new RuntimeException(
                'Missing Excel columns: ' .
                implode(', ', $missingHeaders)
            );
        }


        $headerMap = array_flip($headers);

        $questions = [];
        $errors = [];


        /*
        |--------------------------------------------------------------------------
        | Process rows
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $rowNumber => $row) {

            /*
            |--------------------------------------------------------------------------
            | Skip header row
            |--------------------------------------------------------------------------
            */

            if ($rowNumber === 1) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Question
            |--------------------------------------------------------------------------
            */

            $question = trim(
                (string) (
                    $row[
                        $this->column(
                            $headerMap,
                            'question'
                        )
                    ] ?? ''
                )
            );


            if ($question === '') {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Type
            |--------------------------------------------------------------------------
            */

            $type = strtolower(
                trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                'type'
                            )
                        ] ?? ''
                    )
                )
            );


            /*
            |--------------------------------------------------------------------------
            | Validate type
            |--------------------------------------------------------------------------
            */

            if (!in_array(
                $type,
                [
                    'multiple_choice',
                    'boolean',
                ],
                true
            )) {

                $errors[] = [
                    'row' => $rowNumber,
                    'message' =>
                        "Invalid question type '{$type}'. " .
                        "Use multiple_choice or boolean.",
                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Hint
            |--------------------------------------------------------------------------
            */

            $hint = trim(
                (string) (
                    $row[
                        $this->column(
                            $headerMap,
                            'hint'
                        )
                    ] ?? ''
                )
            );


            /*
            |--------------------------------------------------------------------------
            | BOOLEAN
            |--------------------------------------------------------------------------
            */

            if ($type === 'boolean') {

                $rawAnswer = strtoupper(
                    trim(
                        (string) (
                            $row[
                                $this->column(
                                    $headerMap,
                                    'correct_option'
                                )
                            ] ?? ''
                        )
                    )
                );


                $isCorrect = match ($rawAnswer) {

                    'TRUE',
                    'T',
                    'YES',
                    '1' => true,

                    'FALSE',
                    'F',
                    'NO',
                    '0' => false,

                    default => null,
                };


                if ($isCorrect === null) {

                    $errors[] = [
                        'row' => $rowNumber,
                        'message' =>
                            "Boolean correct_option must be TRUE or FALSE.",
                    ];

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Optional Boolean feedback
                |--------------------------------------------------------------------------
                |
                | We can use:
                |
                | option_a_feedback = True feedback
                | option_b_feedback = False feedback
                |
                | This avoids adding more columns.
                |
                */

                $trueFeedback = trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                'option_a_feedback'
                            )
                        ] ?? ''
                    )
                );


                $falseFeedback = trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                'option_b_feedback'
                            )
                        ] ?? ''
                    )
                );


                $questions[] = [

                    'row' =>
                        $rowNumber,

                    'text' =>
                        $question,

                    'type' =>
                        'boolean',

                    'hint' =>
                        $hint,

                    'is_correct' =>
                        $isCorrect,

                    'options' => [

                        [
                            'text' =>
                                'True',

                            'is_correct' =>
                                $isCorrect,

                            'feedback' =>
                                $trueFeedback,
                        ],

                        [
                            'text' =>
                                'False',

                            'is_correct' =>
                                !$isCorrect,

                            'feedback' =>
                                $falseFeedback,
                        ],
                    ],
                ];


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | MULTIPLE CHOICE
            |--------------------------------------------------------------------------
            */

            $optionColumns = [

                'a' => [
                    'text' =>
                        'option_a',

                    'feedback' =>
                        'option_a_feedback',
                ],

                'b' => [
                    'text' =>
                        'option_b',

                    'feedback' =>
                        'option_b_feedback',
                ],

                'c' => [
                    'text' =>
                        'option_c',

                    'feedback' =>
                        'option_c_feedback',
                ],

                'd' => [
                    'text' =>
                        'option_d',

                    'feedback' =>
                        'option_d_feedback',
                ],
            ];


            $options = [];


            foreach ($optionColumns as $letter => $columns) {

                $optionText = trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                $columns['text']
                            )
                        ] ?? ''
                    )
                );


                $optionFeedback = trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                $columns['feedback']
                            )
                        ] ?? ''
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Skip empty options
                |--------------------------------------------------------------------------
                */

                if ($optionText === '') {
                    continue;
                }


                $options[] = [

                    'text' =>
                        $optionText,

                    'feedback' =>
                        $optionFeedback,

                    'is_correct' =>
                        false,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Minimum two options
            |--------------------------------------------------------------------------
            */

            if (count($options) < 2) {

                $errors[] = [
                    'row' => $rowNumber,
                    'message' =>
                        'Multiple choice questions need at least 2 options.',
                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Correct answer
            |--------------------------------------------------------------------------
            */

            $rawCorrect = strtoupper(
                trim(
                    (string) (
                        $row[
                            $this->column(
                                $headerMap,
                                'correct_option'
                            )
                        ] ?? ''
                    )
                )
            );


            $correctIndex = match ($rawCorrect) {

                'A',
                '1' => 0,

                'B',
                '2' => 1,

                'C',
                '3' => 2,

                'D',
                '4' => 3,

                default => null,
            };


            /*
            |--------------------------------------------------------------------------
            | Validate correct answer
            |--------------------------------------------------------------------------
            */

            if (
                $correctIndex === null ||
                $correctIndex >= count($options)
            ) {

                $errors[] = [

                    'row' =>
                        $rowNumber,

                    'message' =>
                        "Invalid correct_option '{$rawCorrect}'.",
                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Mark correct option
            |--------------------------------------------------------------------------
            */

            foreach ($options as $index => &$option) {

                $option['is_correct'] =
                    $index === $correctIndex;
            }

            unset($option);


            /*
            |--------------------------------------------------------------------------
            | Add question
            |--------------------------------------------------------------------------
            */

            $questions[] = [

                'row' =>
                    $rowNumber,

                'text' =>
                    $question,

                'type' =>
                    'multiple_choice',

                'hint' =>
                    $hint,

                'correct_option' =>
                    $correctIndex,

                'options' =>
                    $options,
            ];
        }


        return [

            'questions' =>
                $questions,

            'errors' =>
                $errors,

            'count' =>
                count($questions),

            'error_count' =>
                count($errors),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Get Excel column by header name
    |--------------------------------------------------------------------------
    */

    private function column(
        array $headerMap,
        string $name
    ): string {

        if (!isset($headerMap[$name])) {

            throw new RuntimeException(
                "Missing Excel column: {$name}"
            );
        }

        return $headerMap[$name];
    }
}