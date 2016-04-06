<?php
namespace app\models;

use yii\base\Model;

class Post extends Model{
	public $id;
	public $title;
	public $user_id;
}