<?php
namespace app\rbac;

use yii\rbac\Rule;
use Yii;

class AuthorRule extends Rule{

	public $name = 'isAuthor';

	public function execute($userId,$item,$params)
	{
		/*return isset($params['post'])? $params['post']->user_id == $userId : false;*/
		//return true;
		if(isset(Yii::$app->user->identity->id) && $params['post']==Yii::$app->user->identity->id){
			return true;
		}else{
			return false;
		}
	}
}