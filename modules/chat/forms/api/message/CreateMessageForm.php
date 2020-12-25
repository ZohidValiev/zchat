<?php
namespace app\modules\chat\forms\api\message;

use yii\base\Model;
use yii\helpers\Html;

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