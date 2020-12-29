<?php
namespace app\forms;


use yii\helpers\Html;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 21:30
 */
class CreateMessageForm extends Model
{
    public $content;

    public function rules()
    {
        return [
            [['content'], 'required'],
            [
                ['content'],
                'filter',
                'filter' => function($value) {
                    return Html::encode($value);
                },
            ],
            [['content'], 'string', 'max' => 255],
        ];
    }


}