<?php

class ResultVerification extends CustomFormValidation {
    private const MAX_SCORE = 100;
    private const PASSING_SCORE = 60;
    private const QUESTION1_WEIGHT = 20;
    private const QUESTION2_WEIGHT = 20;
    private const QUESTION3_WEIGHT = 60;
    private const SIMILARITY_THRESHOLD = 70;

    private $score = 0;
    private $question1Score = 0;
    private $question2Score = 0;
    private $question3Score = 0;

    private $correctAnswers = [
        'сбор данных',
        'разработка концепции',
        'создание эскизов',
        'детализация',
        'проверка',
        'корректировка'
    ];

    public function isQuestion1($data){
        if($data != 'answer1'){
            $this->question1Score = 0;
            return "Вопрос 1: неверный ответ";
        }
        $this->question1Score = self::QUESTION1_WEIGHT;
        return null;
    }

    public function isQuestion2($data){
        if($data != 'answer1'){
            $this->question2Score = 0;
            return "Вопрос 2: неверный ответ";
        }
        $this->question2Score = self::QUESTION2_WEIGHT;
        return null;
    }

    public function isQuestion3($data){
        $answers = explode(',', strtolower($data));
        $correctAnswersCount = count($this->correctAnswers);
        $correctCount = 0;
        $foundAnswers = [];
        
        // Создаем копию массива для подсчета
        $answersToCheck = $this->correctAnswers;

        foreach ($answers as $answer){
            $answer = trim($answer);
            foreach ($answersToCheck as $key => $correctAnswer){
                similar_text($answer, $correctAnswer, $percent);
                if($percent >= self::SIMILARITY_THRESHOLD){
                    $correctCount++;
                    $foundAnswers[] = $correctAnswer;
                    unset($answersToCheck[$key]);
                    break;
                }
            }
        }
        
        $this->question3Score = $correctCount * self::QUESTION3_WEIGHT / $correctAnswersCount;
        $this->score = $this->question1Score + $this->question2Score + $this->question3Score;
        
        if($correctCount >= $correctAnswersCount * 0.7) {
            return null;
        }
        return "Указано $correctCount из $correctAnswersCount верных ответов";
    }

    public function getScore(){
        return $this->score;
    }

    public function isPassed() : bool {
        return $this->score >= self::PASSING_SCORE;
    }
}
    


?>