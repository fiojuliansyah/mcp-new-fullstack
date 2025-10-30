<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'media_url',
        'type_of_answer',
        'answer_point_mark',
        'answer',
        'correct_answer',
    ];

    protected $casts = [
        'answer' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function isCorrect($answer)
    {
        if ($this->type_of_answer === 'true_false') {
            return trim(strtolower($answer)) === trim(strtolower($this->correct_answer));
        }

        if ($this->type_of_answer === 'multiple_choice') {
            $options = $this->answer ?? [];
            foreach ($options as $key => $opt) {
                if (isset($opt['correct']) && $key == $answer) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

}
